<?php

namespace App\Http\Controllers\Amil;

use App\Http\Controllers\Controller;
use App\Models\KasHarianAmil;
use App\Models\TransaksiPenerimaan;
use App\Models\TransaksiPenyaluran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KasHarianAmilController extends Controller
{
    protected $user;
    protected $amil;
    protected $masjid;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user  = Auth::user();
            $this->amil  = $this->user->amil;
            $this->masjid = $this->amil ? $this->amil->masjid : null;

            if (!$this->amil || !$this->masjid) {
                abort(403, 'Data amil atau masjid tidak ditemukan.');
            }

            view()->share('masjid', $this->masjid);

            return $next($request);
        });
    }

    // ============================================================
    // INDEX — Kas Harian Hari Ini
    // ============================================================
    public function index(Request $request)
    {
        $tanggal = $request->filled('tanggal')
            ? Carbon::parse($request->tanggal)
            : now();

        // Ambil atau buat record kas untuk tanggal yang dipilih
        $kas = KasHarianAmil::where('amil_id', $this->amil->id)
            ->where('masjid_id', $this->masjid->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        // Jika tanggal hari ini dan belum ada kas, siapkan data untuk "buka kas"
        $belumBukaKas = is_null($kas) && $tanggal->isToday();

        // Saldo awal yang akan dipakai jika buka kas baru
        $saldoAwalEstimasi = KasHarianAmil::getSaldoAwalHariIni($this->amil->id, $this->masjid->id);

        // Transaksi penerimaan hari ini (jika kas ada)
        $transaksiPenerimaan = collect();
        $transaksiPenyaluran = collect();

        if ($kas) {
            $transaksiPenerimaan = TransaksiPenerimaan::with(['jenisZakat', 'tipeZakat'])
                ->where('amil_id', $this->amil->id)
                ->where('masjid_id', $this->masjid->id)
                ->whereDate('tanggal_transaksi', $tanggal)
                ->where('status', 'verified')
                ->orderByDesc('created_at')
                ->get();

            $transaksiPenyaluran = TransaksiPenyaluran::with(['mustahik', 'kategoriMustahik'])
                ->where('amil_id', $this->amil->id)
                ->where('masjid_id', $this->masjid->id)
                ->whereDate('tanggal_penyaluran', $tanggal)
                ->whereIn('status', ['disetujui', 'disalurkan'])
                ->orderByDesc('created_at')
                ->get();

            // ── Sinkronisasi ringkasan kas dari data transaksi nyata ──
            // Ini memastikan card summary selalu akurat meski ada transaksi
            // yang diinput sebelum/sesudah kas dibuka
            $this->sinkronisasiKas($kas, $transaksiPenerimaan, $transaksiPenyaluran);
            $kas->refresh();
        }

        // Riwayat 7 hari terakhir (collapsible)
        $riwayat7Hari = KasHarianAmil::where('amil_id', $this->amil->id)
            ->where('masjid_id', $this->masjid->id)
            ->where('tanggal', '<', now()->toDateString())
            ->orderByDesc('tanggal')
            ->limit(7)
            ->get();

        return view('amil.kas-harian.index', compact(
            'kas',
            'tanggal',
            'belumBukaKas',
            'saldoAwalEstimasi',
            'transaksiPenerimaan',
            'transaksiPenyaluran',
            'riwayat7Hari'
        ));
    }

    // ============================================================
    // BUKA KAS — Membuat record kas baru untuk hari ini
    // ============================================================
    public function bukaKas(Request $request)
    {
        // Cek apakah kas hari ini sudah ada
        $kasAda = KasHarianAmil::kasHariIni($this->amil->id, $this->masjid->id);

        if ($kasAda) {
            return redirect()->route('kas-harian.index')
                ->with('info', 'Kas hari ini sudah dibuka.');
        }

        DB::beginTransaction();
        try {
            $saldoAwal = KasHarianAmil::getSaldoAwalHariIni($this->amil->id, $this->masjid->id);

            KasHarianAmil::create([
                'amil_id'   => $this->amil->id,
                'masjid_id' => $this->masjid->id,
                'tanggal'   => today(),
                'saldo_awal' => $saldoAwal,
                'saldo_akhir' => $saldoAwal,
                'status'    => 'open',
            ]);

            DB::commit();

            return redirect()->route('kas-harian.index')
                ->with('success', 'Kas harian berhasil dibuka. Selamat bertugas!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error buka kas: ' . $e->getMessage());
            return redirect()->route('kas-harian.index')
                ->with('error', 'Gagal membuka kas: ' . $e->getMessage());
        }
    }

    // ============================================================
    // TUTUP KAS
    // ============================================================
    public function tutupKas(Request $request)
    {
        $kas = KasHarianAmil::kasHariIni($this->amil->id, $this->masjid->id);

        if (!$kas) {
            return redirect()->route('kas-harian.index')
                ->with('error', 'Kas hari ini tidak ditemukan.');
        }

        if ($kas->status === 'closed') {
            return redirect()->route('kas-harian.index')
                ->with('info', 'Kas hari ini sudah ditutup.');
        }

        $request->validate([
            'catatan' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $kas->catatan = $request->catatan;
            $kas->tutupKas();

            DB::commit();

            return redirect()->route('kas-harian.index')
                ->with('success', 'Kas harian berhasil ditutup. Saldo akhir: ' . $kas->saldo_akhir_formatted);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error tutup kas: ' . $e->getMessage());
            return redirect()->route('kas-harian.index')
                ->with('error', 'Gagal menutup kas: ' . $e->getMessage());
        }
    }

    // ============================================================
    // BUKA KEMBALI KAS (Reopen)
    // ============================================================
    public function bukaKembali(Request $request, $uuid)
    {
        $kas = KasHarianAmil::where('uuid', $uuid)
            ->where('amil_id', $this->amil->id)
            ->firstOrFail();

        if ($kas->status !== 'closed') {
            return redirect()->route('kas-harian.index')
                ->with('info', 'Kas ini belum ditutup.');
        }

        // Hanya boleh reopen kas hari ini
        if (!$kas->tanggal->isToday()) {
            return redirect()->route('kas-harian.index')
                ->with('error', 'Hanya kas hari ini yang dapat dibuka kembali.');
        }

        DB::beginTransaction();
        try {
            $kas->bukaKas();
            DB::commit();

            return redirect()->route('kas-harian.index')
                ->with('success', 'Kas berhasil dibuka kembali.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kas-harian.index')
                ->with('error', 'Gagal membuka kembali kas: ' . $e->getMessage());
        }
    }

    // ============================================================
    // SIMPAN CATATAN
    // ============================================================
    public function simpanCatatan(Request $request)
    {
        $kas = KasHarianAmil::kasHariIni($this->amil->id, $this->masjid->id);

        if (!$kas || $kas->status !== 'open') {
            return redirect()->route('kas-harian.index')
                ->with('error', 'Kas tidak ditemukan atau sudah ditutup.');
        }

        $request->validate([
            'catatan' => 'nullable|string|max:1000',
        ]);

        $kas->catatan = $request->catatan;
        $kas->save();

        return redirect()->route('kas-harian.index')
            ->with('success', 'Catatan berhasil disimpan.');
    }

    // ============================================================
    // HISTORY — Riwayat Kas
    // ============================================================
    public function history(Request $request)
    {
        $query = KasHarianAmil::where('amil_id', $this->amil->id)
            ->where('masjid_id', $this->masjid->id);

        // Filter tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->byPeriode($request->start_date, $request->end_date);
        } elseif ($request->filled('start_date')) {
            $query->where('tanggal', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->where('tanggal', '<=', $request->end_date);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $query->orderByDesc('tanggal');

        $kasHarian = $query->paginate(10)->withQueryString();

        // Data untuk chart saldo 30 hari
        $chart30Hari = KasHarianAmil::where('amil_id', $this->amil->id)
            ->where('masjid_id', $this->masjid->id)
            ->where('tanggal', '>=', now()->subDays(29)->toDateString())
            ->orderBy('tanggal')
            ->get(['tanggal', 'saldo_akhir', 'total_penerimaan', 'total_penyaluran']);

        // Stats ringkasan periode yang difilter
        $stats = [
            'total_penerimaan' => $query->sum('total_penerimaan'),
            'total_penyaluran' => $query->sum('total_penyaluran'),
            'total_hari'       => $query->count(),
            'rata_penerimaan'  => $query->count() > 0
                ? $query->avg('total_penerimaan')
                : 0,
        ];

        return view('amil.kas-harian.history', compact(
            'kasHarian',
            'chart30Hari',
            'stats'
        ));
    }


    // ============================================================
    // SINKRONISASI — Hitung ulang ringkasan kas dari transaksi nyata
    // ============================================================

    /**
     * Menghitung ulang semua field ringkasan kas dari transaksi yang benar-benar ada.
     * Dipanggil setiap index() load agar card summary selalu akurat.
     * Tidak mengubah saldo_awal (itu manual/carry-over dari hari sebelumnya).
     */
    protected function sinkronisasiKas(
        KasHarianAmil $kas,
        \Illuminate\Support\Collection $penerimaan,
        \Illuminate\Support\Collection $penyaluran
    ): void {
        $totalPenerimaan = $penerimaan->sum("jumlah");
        $totalPenyaluran = $penyaluran->sum(function ($t) {
            return $t->metode_penyaluran === "barang"
                ? ($t->nilai_barang ?? 0)
                : ($t->jumlah ?? 0);
        });
        $jumlahMasuk       = $penerimaan->count();
        $jumlahKeluar      = $penyaluran->count();
        $jumlahPenjemputan = $penerimaan->where("metode_penerimaan", "dijemput")->count();
        $jumlahLangsung    = $penerimaan->where("metode_penerimaan", "datang_langsung")->count();
        $saldoAkhir        = (float) $kas->saldo_awal + $totalPenerimaan - $totalPenyaluran;
        $needsUpdate =
            (float) $kas->total_penerimaan      !== (float) $totalPenerimaan ||
            (float) $kas->total_penyaluran      !== (float) $totalPenyaluran ||
            (float) $kas->saldo_akhir           !== (float) $saldoAkhir ||
            (int)   $kas->jumlah_transaksi_masuk  !== $jumlahMasuk ||
            (int)   $kas->jumlah_transaksi_keluar !== $jumlahKeluar ||
            (int)   $kas->jumlah_penjemputan      !== $jumlahPenjemputan ||
            (int)   $kas->jumlah_datang_langsung  !== $jumlahLangsung;
        if ($needsUpdate) {
            $kas->total_penerimaan       = $totalPenerimaan;
            $kas->total_penyaluran       = $totalPenyaluran;
            $kas->saldo_akhir            = $saldoAkhir;
            $kas->jumlah_transaksi_masuk = $jumlahMasuk;
            $kas->jumlah_transaksi_keluar= $jumlahKeluar;
            $kas->jumlah_penjemputan     = $jumlahPenjemputan;
            $kas->jumlah_datang_langsung = $jumlahLangsung;
            $kas->save();
        }
    }
    // ============================================================
    // EXPORT EXCEL
    // ============================================================
    public function exportExcel(Request $request)
    {
        $query = KasHarianAmil::where('amil_id', $this->amil->id)
            ->where('masjid_id', $this->masjid->id);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->byPeriode($request->start_date, $request->end_date);
        }
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $data = $query->orderByDesc('tanggal')->get();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Kas Harian');

        // Lebar kolom
        foreach (['A' => 5, 'B' => 16, 'C' => 18, 'D' => 18, 'E' => 18, 'F' => 18,
                  'G' => 12, 'H' => 12, 'I' => 12, 'J' => 12, 'K' => 20] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Header masjid
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', strtoupper($this->masjid->nama ?? 'LAPORAN KAS HARIAN AMIL'));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells('A2:K2');
        $sheet->setCellValue('A2', 'Laporan Kas Harian Amil');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells('A3:K3');
        $sheet->setCellValue('A3', 'Amil: ' . ($this->amil->pengguna->name ?? '-'));
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['size' => 9],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getStyle('A3:K3')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

        // Info ekspor
        $filters = [];
        if ($request->filled('start_date')) $filters[] = 'Dari: ' . Carbon::parse($request->start_date)->format('d/m/Y');
        if ($request->filled('end_date'))   $filters[] = 'Sampai: ' . Carbon::parse($request->end_date)->format('d/m/Y');
        if ($request->filled('status'))     $filters[] = 'Status: ' . ucfirst($request->status);

        $sheet->setCellValue('A5', 'Tanggal Export');
        $sheet->setCellValue('B5', ': ' . now()->format('d/m/Y H:i'));
        $sheet->setCellValue('A6', 'Filter');
        $sheet->setCellValue('B6', ': ' . (count($filters) ? implode(' | ', $filters) : 'Semua Data'));
        $sheet->setCellValue('A7', 'Total Data');
        $sheet->setCellValue('B7', ': ' . $data->count() . ' hari');
        foreach (['A5', 'A6', 'A7'] as $cell) {
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }
        foreach (['B5', 'B6', 'B7'] as $cell) {
            $sheet->mergeCells($cell . ':K' . substr($cell, 1));
        }

        // Header tabel
        $headerRow = 9;
        $headers = [
            'A' => 'No', 'B' => 'Tanggal', 'C' => 'Saldo Awal', 'D' => 'Total Penerimaan',
            'E' => 'Total Penyaluran', 'F' => 'Saldo Akhir', 'G' => 'Trx Masuk',
            'H' => 'Trx Keluar', 'I' => 'Penjemputan', 'J' => 'Datang Langsung', 'K' => 'Status',
        ];
        foreach ($headers as $col => $label) {
            $sheet->setCellValue("{$col}{$headerRow}", $label);
        }
        $sheet->getStyle("A{$headerRow}:K{$headerRow}")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a7a4a']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Data rows
        $row = $headerRow + 1;
        $no  = 1;
        foreach ($data as $kas) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $kas->tanggal->format('d/m/Y'));
            $sheet->setCellValue("C{$row}", (float) $kas->saldo_awal);
            $sheet->setCellValue("D{$row}", (float) $kas->total_penerimaan);
            $sheet->setCellValue("E{$row}", (float) $kas->total_penyaluran);
            $sheet->setCellValue("F{$row}", (float) $kas->saldo_akhir);
            $sheet->setCellValue("G{$row}", $kas->jumlah_transaksi_masuk);
            $sheet->setCellValue("H{$row}", $kas->jumlah_transaksi_keluar);
            $sheet->setCellValue("I{$row}", $kas->jumlah_penjemputan);
            $sheet->setCellValue("J{$row}", $kas->jumlah_datang_langsung);
            $sheet->setCellValue("K{$row}", ucfirst($kas->status));

            foreach (['C', 'D', 'E', 'F'] as $col) {
                $sheet->getStyle("{$col}{$row}")->getNumberFormat()->setFormatCode('#,##0');
            }
            foreach (['A', 'B', 'G', 'H', 'I', 'J', 'K'] as $col) {
                $sheet->getStyle("{$col}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            // Warna status
            $statusColor = $kas->status === 'open' ? 'd4edda' : 'f8f9fa';
            $sheet->getStyle("K{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($statusColor);

            $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            ]);

            if ($no % 2 === 0) {
                foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'] as $col) {
                    $sheet->getStyle("{$col}{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('f8f9fa');
                }
            }
            $row++;
        }

        // Baris total
        $sheet->mergeCells("A{$row}:B{$row}");
        $sheet->setCellValue("A{$row}", 'TOTAL');
        $sheet->setCellValue("C{$row}", (float) $data->sum('saldo_awal'));
        $sheet->setCellValue("D{$row}", (float) $data->sum('total_penerimaan'));
        $sheet->setCellValue("E{$row}", (float) $data->sum('total_penyaluran'));
        $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
            'font'    => ['bold' => true],
            'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e8f5e9']],
            'borders' => ['top' => ['borderStyle' => Border::BORDER_MEDIUM], 'allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        foreach (['C', 'D', 'E', 'F'] as $col) {
            $sheet->getStyle("{$col}{$row}")->getNumberFormat()->setFormatCode('#,##0');
        }

        $sheet->freezePane('A10');

        $filename = 'kas-harian-amil-' . now()->format('Ymd-His') . '.xlsx';
        $writer   = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}