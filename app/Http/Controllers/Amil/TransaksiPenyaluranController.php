<?php

namespace App\Http\Controllers\Amil;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenyaluran;
use App\Models\DokumentasiPenyaluran;
use App\Models\Mustahik;
use App\Models\KategoriMustahik;
use App\Models\JenisZakat;
use App\Models\ProgramZakat;
use App\Models\Amil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TransaksiPenyaluranController extends Controller
{
    /**
     * Daftar transaksi penyaluran
     */
    public function index(Request $request)
    {
        $user    = Auth::user();
        $masjid  = $user->amil->masjid ?? $user->masjid;
        $masjidId = $masjid->id;

        // Stats ringkasan
        $stats = [
            'total'            => TransaksiPenyaluran::byMasjid($masjidId)->count(),
            'total_draft'      => TransaksiPenyaluran::byMasjid($masjidId)->byStatus('draft')->count(),
            'total_disetujui'  => TransaksiPenyaluran::byMasjid($masjidId)->byStatus('disetujui')->count(),
            'total_disalurkan' => TransaksiPenyaluran::byMasjid($masjidId)->byStatus('disalurkan')->count(),
            'total_nominal'    => TransaksiPenyaluran::byMasjid($masjidId)
                ->whereIn('status', ['disetujui', 'disalurkan'])
                ->sum('jumlah'),
            'total_hari_ini'   => TransaksiPenyaluran::byMasjid($masjidId)
                ->whereDate('tanggal_penyaluran', today())
                ->whereIn('status', ['disetujui', 'disalurkan'])
                ->sum('jumlah'),
        ];

        // Query dengan filter
        $query = TransaksiPenyaluran::byMasjid($masjidId)
            ->with(['mustahik', 'kategoriMustahik', 'jenisZakat', 'programZakat', 'amil'])
            ->orderByDesc('tanggal_penyaluran')
            ->orderByDesc('created_at');

        // Filter status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter metode penyaluran
        if ($request->filled('metode_penyaluran')) {
            $query->where('metode_penyaluran', $request->metode_penyaluran);
        }

        // Filter jenis zakat
        if ($request->filled('jenis_zakat_id')) {
            $query->where('jenis_zakat_id', $request->jenis_zakat_id);
        }

        // Filter periode
        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }

        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_penyaluran', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_penyaluran', '<=', $request->end_date);
        }

        // Search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_transaksi', 'like', "%{$q}%")
                    ->orWhereHas('mustahik', fn($m) => $m->where('nama_lengkap', 'like', "%{$q}%"));
            });
        }

        $transaksis      = $query->paginate(10);
        $jenisZakatList  = JenisZakat::all();

        return view('amil.transaksi-penyaluran.index', compact(
            'transaksis',
            'stats',
            'jenisZakatList',
            'masjid'
        ));
    }

    /**
     * Form tambah transaksi penyaluran
     */
    public function create()
    {
        $user    = Auth::user();
        $masjid  = $user->amil->masjid ?? $user->masjid;

        $mustahikList        = Mustahik::where('masjid_id', $masjid->id)
            ->where('status_verifikasi', 'verified')
            ->where('is_active', true)
            ->with('kategoriMustahik')
            ->orderBy('nama_lengkap')
            ->get();

        $kategoriMustahikList = KategoriMustahik::all();
        $jenisZakatList       = JenisZakat::all();
        $programZakatList     = ProgramZakat::where('masjid_id', $masjid->id)
            ->where('status', 'aktif')
            ->get();
        $amilList = Amil::where('masjid_id', $masjid->id)->get();

        $tanggalHariIni      = today()->format('Y-m-d');
        $noTransaksiPreview  = TransaksiPenyaluran::generateNoTransaksi();

        return view('amil.transaksi-penyaluran.create', compact(
            'masjid',
            'mustahikList',
            'kategoriMustahikList',
            'jenisZakatList',
            'programZakatList',
            'amilList',
            'tanggalHariIni',
            'noTransaksiPreview',
        ));
    }

    /**
     * Simpan transaksi penyaluran (status: draft)
     */
    public function store(Request $request)
    {
        $user    = Auth::user();
        $masjid  = $user->amil->masjid ?? $user->masjid;

        $request->validate([
            'mustahik_id'          => 'required|exists:mustahik,id',
            'kategori_mustahik_id' => 'required|exists:kategori_mustahik,id',
            'tanggal_penyaluran'   => 'required|date',
            'waktu_penyaluran'     => 'nullable|date_format:H:i',
            'periode'              => ['nullable', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'jenis_zakat_id'       => 'nullable|exists:jenis_zakat,id',
            'program_zakat_id'     => 'nullable|exists:program_zakat,id',
            'amil_id'              => 'nullable|exists:amil,id',
            'jumlah'               => 'required|numeric|min:0',
            'metode_penyaluran'    => 'required|in:tunai,transfer,barang',
            'detail_barang'        => 'required_if:metode_penyaluran,barang|nullable|string',
            'nilai_barang'         => 'required_if:metode_penyaluran,barang|nullable|numeric|min:0',
            'foto_bukti'           => 'nullable|image|max:2048',
            'keterangan'           => 'nullable|string|max:1000',
            'foto_dokumentasi.*'   => 'nullable|image|max:2048',
        ], [
            'mustahik_id.required'          => 'Mustahik harus dipilih.',
            'kategori_mustahik_id.required' => 'Kategori mustahik harus dipilih.',
            'tanggal_penyaluran.required'   => 'Tanggal penyaluran harus diisi.',
            'jumlah.required'               => 'Jumlah penyaluran harus diisi.',
            'metode_penyaluran.required'    => 'Metode penyaluran harus dipilih.',
            'detail_barang.required_if'     => 'Detail barang wajib diisi untuk metode barang.',
            'nilai_barang.required_if'      => 'Nilai barang wajib diisi untuk metode barang.',
            'periode.regex'                 => 'Format periode tidak valid (contoh: 2024-03).',
        ]);

        DB::beginTransaction();
        try {
            // Upload foto bukti
            $fotoBuktiPath = null;
            if ($request->hasFile('foto_bukti')) {
                $fotoBuktiPath = $request->file('foto_bukti')
                    ->store("penyaluran/{$masjid->id}/bukti", 'public');
            }

            // Upload tanda tangan
            $pathTandaTangan = null;
            if ($request->hasFile('tanda_tangan')) {
                $pathTandaTangan = $request->file('tanda_tangan')
                    ->store("penyaluran/{$masjid->id}/tanda_tangan", 'public');
            }

            $transaksi = TransaksiPenyaluran::create([
                'masjid_id'            => $masjid->id,
                'mustahik_id'          => $request->mustahik_id,
                'kategori_mustahik_id' => $request->kategori_mustahik_id,
                'tanggal_penyaluran'   => $request->tanggal_penyaluran,
                'waktu_penyaluran'     => $request->waktu_penyaluran,
                'periode'              => $request->periode,
                'jenis_zakat_id'       => $request->jenis_zakat_id,
                'program_zakat_id'     => $request->program_zakat_id,
                'amil_id'              => $request->amil_id ?? $user->amil?->id,
                'jumlah'               => $request->metode_penyaluran === 'barang' ? 0 : $request->jumlah,
                'metode_penyaluran'    => $request->metode_penyaluran,
                'detail_barang'        => $request->detail_barang,
                'nilai_barang'         => $request->nilai_barang,
                'foto_bukti'           => $fotoBuktiPath,
                'path_tanda_tangan'    => $pathTandaTangan,
                'keterangan'           => $request->keterangan,
                'status'               => 'draft',
            ]);

            // Upload foto dokumentasi (multiple)
            if ($request->hasFile('foto_dokumentasi')) {
                foreach ($request->file('foto_dokumentasi') as $index => $foto) {
                    $path = $foto->store("penyaluran/{$masjid->id}/dokumentasi", 'public');
                    DokumentasiPenyaluran::create([
                        'transaksi_penyaluran_id' => $transaksi->id,
                        'path_foto'               => $path,
                        'keterangan_foto'         => $request->keterangan_foto[$index] ?? null,
                        'urutan'                  => $index,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('transaksi-penyaluran.show', $transaksi->uuid)
                ->with('success', "Transaksi penyaluran {$transaksi->no_transaksi} berhasil disimpan sebagai draft.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function show(TransaksiPenyaluran $transaksiPenyaluran)
    {
        $user    = Auth::user();
        $masjid  = $user->amil->masjid ?? $user->masjid;

        abort_if($transaksiPenyaluran->masjid_id !== $masjid->id, 403);

        // Load semua relasi yang diperlukan
        $transaksiPenyaluran->load([
            'mustahik',
            'kategoriMustahik',
            'jenisZakat',
            'programZakat',
            'amil',
            'approvedBy',
            'disalurkanOleh',
            'dibatalkanOleh',
            'dokumentasi',
        ]);

        // Ganti nama variabel agar sesuai dengan view (dari $transaksiPenyaluran jadi $transaksi)
        $transaksi = $transaksiPenyaluran;

        return view('amil.transaksi-penyaluran.show', compact('transaksi', 'masjid'));
    }

    public function edit(TransaksiPenyaluran $transaksiPenyaluran)
    {
        $user    = Auth::user();
        $masjid  = $user->amil->masjid ?? $user->masjid;

        abort_if($transaksiPenyaluran->masjid_id !== $masjid->id, 403);
        abort_if($transaksiPenyaluran->status !== 'draft', 403, 'Hanya transaksi berstatus draft yang dapat diedit.');

        $mustahikList         = Mustahik::where('masjid_id', $masjid->id)
            ->where('status_verifikasi', 'verified')
            ->where('is_active', true)
            ->with('kategoriMustahik')
            ->orderBy('nama_lengkap')
            ->get();
        $kategoriMustahikList = KategoriMustahik::all();
        $jenisZakatList       = JenisZakat::all();
        $programZakatList     = ProgramZakat::where('masjid_id', $masjid->id)->where('status', 'aktif')->get();
        $amilList             = Amil::where('masjid_id', $masjid->id)->get();

        $transaksiPenyaluran->load('dokumentasi');

        // Rename ke $transaksi agar konsisten dengan view
        $transaksi = $transaksiPenyaluran;

        return view('amil.transaksi-penyaluran.edit', compact(
            'transaksi',
            'masjid',
            'mustahikList',
            'kategoriMustahikList',
            'jenisZakatList',
            'programZakatList',
            'amilList'
        ));
    }
    /**
     * Update transaksi (hanya draft)
     */
    public function update(Request $request, TransaksiPenyaluran $transaksiPenyaluran)
    {
        $user    = Auth::user();
        $masjid  = $user->amil->masjid ?? $user->masjid;

        abort_if($transaksiPenyaluran->masjid_id !== $masjid->id, 403);
        abort_if($transaksiPenyaluran->status !== 'draft', 403);

        $request->validate([
            'mustahik_id'          => 'required|exists:mustahik,id',
            'kategori_mustahik_id' => 'required|exists:kategori_mustahik,id',
            'tanggal_penyaluran'   => 'required|date',
            'waktu_penyaluran'     => 'nullable|date_format:H:i',
            'periode'              => ['nullable', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'jenis_zakat_id'       => 'nullable|exists:jenis_zakat,id',
            'program_zakat_id'     => 'nullable|exists:program_zakat,id',
            'amil_id'              => 'nullable|exists:amil,id',
            'jumlah'               => 'required|numeric|min:0',
            'metode_penyaluran'    => 'required|in:tunai,transfer,barang',
            'detail_barang'        => 'required_if:metode_penyaluran,barang|nullable|string',
            'nilai_barang'         => 'required_if:metode_penyaluran,barang|nullable|numeric|min:0',
            'foto_bukti'           => 'nullable|image|max:2048',
            'keterangan'           => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only([
                'mustahik_id',
                'kategori_mustahik_id',
                'tanggal_penyaluran',
                'waktu_penyaluran',
                'periode',
                'jenis_zakat_id',
                'program_zakat_id',
                'amil_id',
                'metode_penyaluran',
                'detail_barang',
                'nilai_barang',
                'keterangan',
            ]);

            $data['jumlah'] = $request->metode_penyaluran === 'barang' ? 0 : $request->jumlah;

            // Upload foto bukti baru (jika ada)
            if ($request->hasFile('foto_bukti')) {
                if ($transaksiPenyaluran->foto_bukti) {
                    Storage::disk('public')->delete($transaksiPenyaluran->foto_bukti);
                }
                $data['foto_bukti'] = $request->file('foto_bukti')
                    ->store("penyaluran/{$masjid->id}/bukti", 'public');
            }

            // Upload tanda tangan baru (jika ada)
            if ($request->hasFile('tanda_tangan')) {
                if ($transaksiPenyaluran->path_tanda_tangan) {
                    Storage::disk('public')->delete($transaksiPenyaluran->path_tanda_tangan);
                }
                $data['path_tanda_tangan'] = $request->file('tanda_tangan')
                    ->store("penyaluran/{$masjid->id}/tanda_tangan", 'public');
            }

            $transaksiPenyaluran->update($data);

            // Tambah foto dokumentasi baru
            if ($request->hasFile('foto_dokumentasi')) {
                $lastUrutan = $transaksiPenyaluran->dokumentasi()->max('urutan') ?? -1;
                foreach ($request->file('foto_dokumentasi') as $index => $foto) {
                    $path = $foto->store("penyaluran/{$masjid->id}/dokumentasi", 'public');
                    DokumentasiPenyaluran::create([
                        'transaksi_penyaluran_id' => $transaksiPenyaluran->id,
                        'path_foto'               => $path,
                        'keterangan_foto'         => $request->keterangan_foto[$index] ?? null,
                        'urutan'                  => $lastUrutan + $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('transaksi-penyaluran.show', $transaksiPenyaluran->uuid)
                ->with('success', 'Transaksi penyaluran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui: ' . $e->getMessage()]);
        }
    }

    /**
     * Hapus transaksi (hanya draft)
     */
    public function destroy(TransaksiPenyaluran $transaksiPenyaluran)
    {
        $user   = Auth::user();
        $masjid = $user->amil->masjid ?? $user->masjid;

        abort_if($transaksiPenyaluran->masjid_id !== $masjid->id, 403);
        abort_if($transaksiPenyaluran->status !== 'draft', 403, 'Hanya draft yang dapat dihapus.');

        $transaksiPenyaluran->delete();

        return redirect()
            ->route('transaksi-penyaluran.index')
            ->with('success', 'Transaksi penyaluran berhasil dihapus.');
    }

    public function konfirmasiDisalurkan(Request $request, TransaksiPenyaluran $transaksiPenyaluran)
    {
        $user   = Auth::user();
        $masjid = $user->amil->masjid ?? $user->masjid;

        abort_if($transaksiPenyaluran->masjid_id !== $masjid->id, 403);
        abort_if($transaksiPenyaluran->status !== 'disetujui', 403, 'Hanya transaksi yang sudah disetujui yang dapat dikonfirmasi.');

        $transaksiPenyaluran->update([
            'status'          => 'disalurkan',
            'disalurkan_oleh' => $user->id,
            'disalurkan_at'   => now(),
        ]);

        return redirect()
            ->route('transaksi-penyaluran.cetak', $transaksiPenyaluran->uuid)
            ->with('success', 'Transaksi berhasil dikonfirmasi. Silakan cetak kwitansi penyaluran.');
    }

    /**
     * Hapus foto dokumentasi individual
     */
    public function hapusDokumentasi(DokumentasiPenyaluran $dokumentasi)
    {
        $user   = Auth::user();
        $masjid = $user->amil->masjid ?? $user->masjid;

        $transaksi = $dokumentasi->transaksi;
        abort_if($transaksi->masjid_id !== $masjid->id, 403);
        abort_if($transaksi->status !== 'draft', 403);

        Storage::disk('public')->delete($dokumentasi->path_foto);
        $dokumentasi->delete();

        return back()->with('success', 'Foto dokumentasi berhasil dihapus.');
    }

    private function getMasjid()
    {
        $user = Auth::user();

        if ($user->peran === 'admin_masjid') {
            return $user->masjid; // relasi belongsTo via masjid_id
        }

        // amil
        return optional($user->amil)->masjid;
    }

    public function cetak(TransaksiPenyaluran $transaksiPenyaluran)
    {
        $user   = Auth::user();
        $masjid = $user->amil->masjid ?? $user->masjid;

        abort_if($transaksiPenyaluran->masjid_id !== $masjid->id, 403);
        abort_if($transaksiPenyaluran->status !== 'disalurkan', 403, 'Hanya transaksi yang sudah disalurkan yang dapat dicetak.');

        $transaksiPenyaluran->load([
            'masjid',
            'mustahik',
            'kategoriMustahik',
            'jenisZakat',
            'programZakat',
            'amil.pengguna',
            'dokumentasi',
        ]);

        $transaksi = $transaksiPenyaluran;

        return view('amil.transaksi-penyaluran.cetak', compact('transaksi'));
    }

    /**
     * Ambil query transaksi dengan filter (dipakai bersama oleh exportPdf & exportExcel)
     */
    private function buildExportQuery(Request $request, int $masjidId)
    {
        $query = TransaksiPenyaluran::byMasjid($masjidId)
            ->with(['mustahik', 'kategoriMustahik', 'jenisZakat', 'programZakat', 'amil.pengguna'])
            ->orderByDesc('tanggal_penyaluran')
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('metode_penyaluran')) {
            $query->where('metode_penyaluran', $request->metode_penyaluran);
        }
        if ($request->filled('jenis_zakat_id')) {
            $query->where('jenis_zakat_id', $request->jenis_zakat_id);
        }
        if ($request->filled('periode')) {
            $query->byPeriode($request->periode);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_penyaluran', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_penyaluran', '<=', $request->end_date);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_transaksi', 'like', "%{$q}%")
                    ->orWhereHas('mustahik', fn($m) => $m->where('nama_lengkap', 'like', "%{$q}%"));
            });
        }

        return $query;
    }

    /**
     * Export PDF laporan transaksi penyaluran
     * Membutuhkan package: barryvdh/laravel-dompdf
     * Install: composer require barryvdh/laravel-dompdf
     */
    public function exportPdf(Request $request)
    {
        $user     = Auth::user();
        $masjid   = $user->amil->masjid ?? $user->masjid;
        $masjidId = $masjid->id;

        $transaksis = $this->buildExportQuery($request, $masjidId)->get();

        // Hitung ringkasan
        $totalTransaksi  = $transaksis->count();
        $totalDraft      = $transaksis->where('status', 'draft')->count();
        $totalDiSetujui  = $transaksis->where('status', 'disetujui')->count();
        $totalDisalurkan = $transaksis->where('status', 'disalurkan')->count();
        $totalDibatalkan = $transaksis->where('status', 'dibatalkan')->count();
        $totalNominal    = $transaksis
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->sum(function ($t) {
                return $t->metode_penyaluran === 'barang'
                    ? ($t->nilai_barang ?? 0)
                    : ($t->jumlah ?? 0);
            });

        $jenisZakatList = JenisZakat::all();
        $filters        = $request->only([
            'q',
            'status',
            'metode_penyaluran',
            'jenis_zakat_id',
            'periode',
            'start_date',
            'end_date',
        ]);
        $tanggalExport  = Carbon::now()->locale('id')->translatedFormat('l, d F Y H:i') . ' WIB';

        $html = view('amil.transaksi-penyaluran.export.pdf', compact(
            'transaksis',
            'masjid',
            'filters',
            'jenisZakatList',
            'totalTransaksi',
            'totalDraft',
            'totalDiSetujui',
            'totalDisalurkan',
            'totalDibatalkan',
            'totalNominal',
            'tanggalExport',
            'user',
        ))->render();

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions([
            'defaultFont'          => 'helvetica',
            'isRemoteEnabled'      => false,
            'isHtml5ParserEnabled' => true,
        ]);

        $filename = 'laporan-penyaluran-' . Carbon::now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export Excel laporan transaksi penyaluran
     * Membutuhkan package: phpoffice/phpspreadsheet
     * Install: composer require phpoffice/phpspreadsheet
     */
    public function exportExcel(Request $request)
    {
        $user     = Auth::user();
        $masjid   = $user->amil->masjid ?? $user->masjid;
        $masjidId = $masjid->id;

        $transaksis = $this->buildExportQuery($request, $masjidId)->get();

        // ── Buat spreadsheet ──────────────────────────────────────────────────
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Penyaluran');

        // Lebar kolom
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(14);
        $sheet->getColumnDimension('D')->setWidth(28);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(22);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(18);
        $sheet->getColumnDimension('J')->setWidth(14);
        $sheet->getColumnDimension('K')->setWidth(13);
        $sheet->getColumnDimension('L')->setWidth(22);
        $sheet->getColumnDimension('M')->setWidth(35);

        // ── Baris 1: Judul ────────────────────────────────────────────────────
        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A1', strtoupper($masjid->nama ?? 'LAPORAN TRANSAKSI PENYALURAN ZAKAT'));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Baris 2: Sub judul ────────────────────────────────────────────────
        $sheet->mergeCells('A2:M2');
        $sheet->setCellValue('A2', 'Laporan Transaksi Penyaluran Zakat');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 11, 'italic' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Baris 3: Alamat ───────────────────────────────────────────────────
        $alamat = implode('', array_filter([
            $masjid->alamat ?? '',
            $masjid->kelurahan_nama ? ', Kel. ' . $masjid->kelurahan_nama : '',
            $masjid->kecamatan_nama ? ', Kec. ' . $masjid->kecamatan_nama : '',
            $masjid->kota_nama      ? ', ' . $masjid->kota_nama            : '',
        ]));
        $sheet->mergeCells('A3:M3');
        $sheet->setCellValue('A3', $alamat);
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['size' => 9, 'italic' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Garis pemisah header
        $sheet->getStyle('A3:M3')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

        // ── Baris 5-8: Info ekspor ────────────────────────────────────────────
        $filters   = $request->only(['q', 'status', 'metode_penyaluran', 'jenis_zakat_id', 'periode', 'start_date', 'end_date']);
        $filterStr = [];
        if (!empty($filters['status'])) {
            $filterStr[] = 'Status: ' . ucfirst($filters['status']);
        }
        if (!empty($filters['metode_penyaluran'])) {
            $filterStr[] = 'Metode: ' . ucfirst($filters['metode_penyaluran']);
        }
        if (!empty($filters['jenis_zakat_id'])) {
            $jenis = JenisZakat::find($filters['jenis_zakat_id']);
            $filterStr[] = 'Jenis Zakat: ' . ($jenis->nama ?? '-');
        }
        if (!empty($filters['start_date'])) {
            $filterStr[] = 'Dari: ' . Carbon::parse($filters['start_date'])->format('d/m/Y');
        }
        if (!empty($filters['end_date'])) {
            $filterStr[] = 'Sampai: ' . Carbon::parse($filters['end_date'])->format('d/m/Y');
        }
        if (!empty($filters['periode'])) {
            $filterStr[] = 'Periode: ' . $filters['periode'];
        }
        if (!empty($filters['q'])) {
            $filterStr[] = "Pencarian: '{$filters['q']}'";
        }

        $infoRows = [
            ['Tanggal Ekspor', Carbon::now()->locale('id')->translatedFormat('l, d F Y H:i') . ' WIB'],
            ['Filter',         count($filterStr) ? implode(' | ', $filterStr) : 'Semua Data'],
            ['Total Data',     $transaksis->count() . ' transaksi'],
            ['Petugas',        $user->name ?? $user->username ?? 'System'],
        ];

        $row = 5;
        foreach ($infoRows as [$label, $value]) {
            $sheet->setCellValue("A{$row}", $label);
            $sheet->setCellValue("B{$row}", ': ' . $value);
            $sheet->mergeCells("B{$row}:M{$row}");
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $row++;
        }

        // ── Header tabel (baris 10) ───────────────────────────────────────────
        $headerRow = 10;
        $headers   = [
            'A' => 'No',
            'B' => 'No. Transaksi',
            'C' => 'Tanggal',
            'D' => 'Mustahik',
            'E' => 'Kategori Mustahik',
            'F' => 'Jenis Zakat',
            'G' => 'Program Zakat',
            'H' => 'Jumlah (Rp)',
            'I' => 'Detail Barang',
            'J' => 'Metode',
            'K' => 'Status',
            'L' => 'Amil',
            'M' => 'Keterangan',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue("{$col}{$headerRow}", $label);
        }

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a7a4a']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ];
        $sheet->getStyle("A{$headerRow}:M{$headerRow}")->applyFromArray($headerStyle);
        $sheet->getRowDimension($headerRow)->setRowHeight(20);

        // ── Data rows ─────────────────────────────────────────────────────────
        $dataRow   = $headerRow + 1;
        $no        = 1;
        $grandTotal = 0;

        foreach ($transaksis as $transaksi) {
            $nominal = $transaksi->metode_penyaluran === 'barang'
                ? ($transaksi->nilai_barang ?? 0)
                : ($transaksi->jumlah ?? 0);

            $grandTotal += $nominal;

            $statusText = match ($transaksi->status) {
                'draft'      => 'Draft',
                'disetujui'  => 'Disetujui',
                'disalurkan' => 'Disalurkan',
                'dibatalkan' => 'Dibatalkan',
                default      => $transaksi->status,
            };
            $metodeText = match ($transaksi->metode_penyaluran) {
                'tunai'    => 'Tunai',
                'transfer' => 'Transfer',
                'barang'   => 'Barang',
                default    => '-',
            };

            $sheet->setCellValue("A{$dataRow}", $no++);
            $sheet->setCellValue("B{$dataRow}", $transaksi->no_transaksi);
            $sheet->setCellValue("C{$dataRow}", $transaksi->tanggal_penyaluran->format('d/m/Y'));
            $sheet->setCellValue("D{$dataRow}", $transaksi->mustahik->nama_lengkap ?? '-');
            $sheet->setCellValue("E{$dataRow}", $transaksi->kategoriMustahik->nama ?? '-');
            $sheet->setCellValue("F{$dataRow}", $transaksi->jenisZakat->nama ?? '-');
            $sheet->setCellValue("G{$dataRow}", $transaksi->programZakat->nama_program ?? '-');
            $sheet->setCellValue("H{$dataRow}", $nominal > 0 ? $nominal : 0);
            $sheet->setCellValue("I{$dataRow}", $transaksi->detail_barang ?? '-');
            $sheet->setCellValue("J{$dataRow}", $metodeText);
            $sheet->setCellValue("K{$dataRow}", $statusText);
            $sheet->setCellValue("L{$dataRow}", $transaksi->amil->pengguna->name ?? $transaksi->amil->nama_lengkap ?? '-');
            $sheet->setCellValue("M{$dataRow}", $transaksi->keterangan ?? '-');

            // Format angka
            $sheet->getStyle("H{$dataRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("A{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("J{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("K{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("H{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Warna status
            $statusColor = match ($transaksi->status) {
                'disalurkan' => 'd4edda',
                'disetujui'  => 'cce5ff',
                'dibatalkan' => 'f8d7da',
                default      => 'fff3cd', // draft
            };
            $sheet->getStyle("K{$dataRow}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB($statusColor);

            // Border semua sel
            $sheet->getStyle("A{$dataRow}:M{$dataRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            ]);

            // Warna baris selang-seling
            if ($no % 2 === 0) {
                foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'L', 'M'] as $col) {
                    $sheet->getStyle("{$col}{$dataRow}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('f8f9fa');
                }
            }

            $dataRow++;
        }

        // ── Baris total ───────────────────────────────────────────────────────
        $sheet->mergeCells("A{$dataRow}:G{$dataRow}");
        $sheet->setCellValue("A{$dataRow}", 'TOTAL NOMINAL (Disalurkan & Disetujui)');
        $sheet->setCellValue("H{$dataRow}", $transaksis
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->sum(fn($t) => $t->metode_penyaluran === 'barang' ? ($t->nilai_barang ?? 0) : ($t->jumlah ?? 0)));
        $sheet->getStyle("H{$dataRow}")->getNumberFormat()->setFormatCode('#,##0');
        $totalStyle = [
            'font'      => ['bold' => true],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e8f5e9']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            'borders'   => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                'top'        => ['borderStyle' => Border::BORDER_MEDIUM],
            ],
        ];
        $sheet->getStyle("A{$dataRow}:M{$dataRow}")->applyFromArray($totalStyle);
        $sheet->getStyle("A{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // ── Freeze header ─────────────────────────────────────────────────────
        $sheet->freezePane('A11');

        // ── Auto-filter ───────────────────────────────────────────────────────
        $lastDataRow = $dataRow - 1;
        if ($lastDataRow >= $headerRow + 1) {
            $sheet->setAutoFilter("A{$headerRow}:M{$lastDataRow}");
        }

        // ── Output ────────────────────────────────────────────────────────────
        $filename = 'laporan-penyaluran-' . Carbon::now()->format('Ymd-His') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}