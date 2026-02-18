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
            'transaksis', 'stats', 'jenisZakatList', 'masjid'
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
        'transaksi', 'masjid',
        'mustahikList', 'kategoriMustahikList',
        'jenisZakatList', 'programZakatList', 'amilList'
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
                'mustahik_id', 'kategori_mustahik_id', 'tanggal_penyaluran',
                'waktu_penyaluran', 'periode', 'jenis_zakat_id', 'program_zakat_id',
                'amil_id', 'metode_penyaluran', 'detail_barang', 'nilai_barang', 'keterangan',
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

    /**
     * Konfirmasi sudah disalurkan ke mustahik (amil)
     * Status: disetujui → disalurkan
     */
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
            ->route('transaksi-penyaluran.show', $transaksiPenyaluran->uuid)
            ->with('success', 'Transaksi berhasil dikonfirmasi sebagai sudah disalurkan.');
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
}