<?php

namespace App\Http\Controllers\Admin_masjid;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenerimaan;
use App\Models\TransaksiPenyaluran;
use App\Models\LaporanKeuanganMasjid;
use App\Models\JenisZakat;
use App\Models\KategoriMustahik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanKeuanganController extends Controller
{
    // ================================================================
    // INDEX — Daftar laporan per bulan dalam satu tahun
    // ================================================================

    public function index(Request $request)
    {
        $user     = Auth::user();
        $masjidId = $user->masjid_id;
        $tahun    = (int) $request->get('tahun', now()->year);

        // Ambil semua laporan yang sudah digenerate untuk tahun ini
        $laporanDb = LaporanKeuanganMasjid::where('masjid_id', $masjidId)
            ->where('tahun', $tahun)
            ->get()
            ->keyBy('bulan');

        // Buat 12 baris — bulan yang belum digenerate muncul sebagai dummy
        $laporanTahunan = collect();
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            if ($laporanDb->has($bulan)) {
                $laporanTahunan->push($laporanDb[$bulan]);
            } else {
                $tanggalBulan = Carbon::createFromDate($tahun, $bulan, 1);
                $lap = new \stdClass();
                $lap->uuid             = null;
                $lap->bulan            = $bulan;
                $lap->tahun            = $tahun;
                $lap->nama_bulan       = $tanggalBulan->translatedFormat('F');
                $lap->saldo_awal       = 0;
                $lap->total_penerimaan = 0;
                $lap->total_penyaluran = 0;
                $lap->saldo_akhir      = 0;
                $lap->jumlah_muzakki   = 0;
                $lap->jumlah_mustahik  = 0;
                $lap->status           = null;
                $lap->status_badge     = '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Belum dibuat</span>';
                $lap->can_generate     = $tanggalBulan->lte(now());
                $lap->can_publish      = false;
                $laporanTahunan->push($lap);
            }
        }

        // Chart data — diambil langsung dari transaksi (bukan dari tabel laporan)
        $summaryPenerimaan = TransaksiPenerimaan::where('masjid_id', $masjidId)
            ->where('status', 'verified')
            ->whereYear('tanggal_transaksi', $tahun)
            ->selectRaw('MONTH(tanggal_transaksi) as bulan, SUM(jumlah) as total')
            ->groupBy(DB::raw('MONTH(tanggal_transaksi)'))
            ->pluck('total', 'bulan');

        $summaryPenyaluran = TransaksiPenyaluran::where('masjid_id', $masjidId)
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->whereYear('tanggal_penyaluran', $tahun)
            ->selectRaw('MONTH(tanggal_penyaluran) as bulan, SUM(CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END) as total')
            ->groupBy(DB::raw('MONTH(tanggal_penyaluran)'))
            ->pluck('total', 'bulan');

        $chartData = ['labels' => [], 'penerimaan' => [], 'penyaluran' => []];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $chartData['labels'][]     = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('M');
            $chartData['penerimaan'][] = (float) ($summaryPenerimaan[$bulan] ?? 0);
            $chartData['penyaluran'][] = (float) ($summaryPenyaluran[$bulan] ?? 0);
        }

        $availableYears = range(now()->year, max(now()->year - 5, 2020));

        return view('admin-masjid.laporan-keuangan.index', compact(
            'laporanTahunan', 'tahun', 'availableYears', 'chartData'
        ));
    }

    // ================================================================
    // SHOW — Detail laporan satu bulan
    // ================================================================

    public function show($uuid)
    {
        $user     = Auth::user();
        $masjidId = $user->masjid_id;

        $laporan = LaporanKeuanganMasjid::where('uuid', $uuid)
            ->where('masjid_id', $masjidId)
            ->with(['masjid', 'creator'])
            ->firstOrFail();

        $periodeAwal  = $laporan->periode_mulai;
        $periodeAkhir = $laporan->periode_selesai;

        // Detail penerimaan per jenis zakat
        $detailPenerimaan = TransaksiPenerimaan::where('transaksi_penerimaan.masjid_id', $masjidId)
            ->where('transaksi_penerimaan.status', 'verified')
            ->whereBetween('transaksi_penerimaan.tanggal_transaksi', [$periodeAwal, $periodeAkhir])
            ->leftJoin('jenis_zakat', 'transaksi_penerimaan.jenis_zakat_id', '=', 'jenis_zakat.id')
            ->select(
                DB::raw('COALESCE(jenis_zakat.nama, "Lainnya") as jenis_zakat'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(transaksi_penerimaan.jumlah) as jumlah')
            )
            ->groupBy('jenis_zakat.id', 'jenis_zakat.nama')
            ->orderByDesc('jumlah')
            ->get()
            ->toArray();

        // Detail penyaluran per kategori mustahik
        $detailPenyaluran = TransaksiPenyaluran::where('transaksi_penyaluran.masjid_id', $masjidId)
            ->whereIn('transaksi_penyaluran.status', ['disetujui', 'disalurkan'])
            ->whereBetween('transaksi_penyaluran.tanggal_penyaluran', [$periodeAwal, $periodeAkhir])
            ->leftJoin('kategori_mustahik', 'transaksi_penyaluran.kategori_mustahik_id', '=', 'kategori_mustahik.id')
            ->select(
                DB::raw('COALESCE(kategori_mustahik.nama, "Lainnya") as kategori'),
                DB::raw('COUNT(DISTINCT transaksi_penyaluran.mustahik_id) as count'),
                DB::raw('SUM(CASE WHEN transaksi_penyaluran.metode_penyaluran = "barang" THEN COALESCE(transaksi_penyaluran.nilai_barang, 0) ELSE transaksi_penyaluran.jumlah END) as jumlah')
            )
            ->groupBy('kategori_mustahik.id', 'kategori_mustahik.nama')
            ->orderByDesc('jumlah')
            ->get()
            ->toArray();

        // Chart pie data
        $colors = ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6', '#F97316'];

        $chartPenerimaan = [
            'labels'          => array_column($detailPenerimaan, 'jenis_zakat'),
            'data'            => array_map('floatval', array_column($detailPenerimaan, 'jumlah')),
            'backgroundColor' => array_slice($colors, 0, count($detailPenerimaan)),
        ];

        $chartPenyaluran = [
            'labels'          => array_column($detailPenyaluran, 'kategori'),
            'data'            => array_map('floatval', array_column($detailPenyaluran, 'jumlah')),
            'backgroundColor' => array_slice($colors, 0, count($detailPenyaluran)),
        ];

        $tanggalCetak = now()->format('d F Y H:i:s');

        return view('admin-masjid.laporan-keuangan.show', compact(
            'laporan', 'detailPenerimaan', 'detailPenyaluran',
            'chartPenerimaan', 'chartPenyaluran', 'tanggalCetak'
        ));
    }

    // ================================================================
    // GENERATE — Hitung dan simpan laporan satu bulan
    // ================================================================

    public function generate($tahun, $bulan)
    {
        $user     = Auth::user();
        $masjidId = $user->masjid_id;

        $periodeAwal  = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $periodeAkhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        DB::beginTransaction();
        try {
            // Penerimaan
            $totalPenerimaan = TransaksiPenerimaan::where('masjid_id', $masjidId)
                ->where('status', 'verified')
                ->whereBetween('tanggal_transaksi', [$periodeAwal, $periodeAkhir])
                ->sum('jumlah');

            $jumlahMuzakki = TransaksiPenerimaan::where('masjid_id', $masjidId)
                ->where('status', 'verified')
                ->whereBetween('tanggal_transaksi', [$periodeAwal, $periodeAkhir])
                ->distinct('muzakki_nama')
                ->count('muzakki_nama');

            $jumlahTransaksiMasuk = TransaksiPenerimaan::where('masjid_id', $masjidId)
                ->where('status', 'verified')
                ->whereBetween('tanggal_transaksi', [$periodeAwal, $periodeAkhir])
                ->count();

            // Penyaluran
            $totalPenyaluran = TransaksiPenyaluran::where('masjid_id', $masjidId)
                ->whereIn('status', ['disetujui', 'disalurkan'])
                ->whereBetween('tanggal_penyaluran', [$periodeAwal, $periodeAkhir])
                ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END'));

            $jumlahMustahik = TransaksiPenyaluran::where('masjid_id', $masjidId)
                ->whereIn('status', ['disetujui', 'disalurkan'])
                ->whereBetween('tanggal_penyaluran', [$periodeAwal, $periodeAkhir])
                ->distinct('mustahik_id')
                ->count('mustahik_id');

            $jumlahTransaksiKeluar = TransaksiPenyaluran::where('masjid_id', $masjidId)
                ->whereIn('status', ['disetujui', 'disalurkan'])
                ->whereBetween('tanggal_penyaluran', [$periodeAwal, $periodeAkhir])
                ->count();

            // Saldo awal dari laporan bulan sebelumnya
            $laporanSebelumnya = LaporanKeuanganMasjid::where('masjid_id', $masjidId)
                ->where(function ($q) use ($tahun, $bulan) {
                    $q->where(function ($inner) use ($tahun, $bulan) {
                        $inner->where('tahun', $tahun)->where('bulan', '<', $bulan);
                    })->orWhere('tahun', '<', $tahun);
                })
                ->orderByDesc('tahun')
                ->orderByDesc('bulan')
                ->first();

            $saldoAwal  = $laporanSebelumnya ? $laporanSebelumnya->saldo_akhir : 0;
            $saldoAkhir = $saldoAwal + $totalPenerimaan - $totalPenyaluran;

            $laporan = LaporanKeuanganMasjid::updateOrCreate(
                [
                    'masjid_id' => $masjidId,
                    'tahun'     => $tahun,
                    'bulan'     => $bulan,
                ],
                [
                    'periode_mulai'           => $periodeAwal,
                    'periode_selesai'         => $periodeAkhir,
                    'saldo_awal'              => $saldoAwal,
                    'total_penerimaan'        => $totalPenerimaan,
                    'total_penyaluran'        => $totalPenyaluran,
                    'saldo_akhir'             => $saldoAkhir,
                    'jumlah_muzakki'          => $jumlahMuzakki,
                    'jumlah_mustahik'         => $jumlahMustahik,
                    'jumlah_transaksi_masuk'  => $jumlahTransaksiMasuk,
                    'jumlah_transaksi_keluar' => $jumlahTransaksiKeluar,
                    'status'                  => 'draft',
                    'created_by'              => $user->id,
                ]
            );

            DB::commit();

            return redirect()->route('laporan-keuangan.show', $laporan->uuid)
                ->with('success', 'Laporan bulan ' . Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') . ' berhasil digenerate.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal generate laporan: ' . $e->getMessage());
        }
    }

    // ================================================================
    // PUBLISH
    // ================================================================

    public function publish($uuid)
    {
        $user    = Auth::user();
        $laporan = LaporanKeuanganMasjid::where('uuid', $uuid)
            ->where('masjid_id', $user->masjid_id)
            ->firstOrFail();

        if ($laporan->status !== 'draft') {
            return redirect()->back()->with('error', 'Hanya laporan berstatus draft yang dapat dipublikasi.');
        }

        $laporan->update([
            'status'       => 'published',
            'published_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dipublikasi.');
    }

    // ================================================================
    // PUBLIC INDEX — Transparansi (hanya laporan published)
    // ================================================================

    public function publicIndex(Request $request)
    {
        $user     = Auth::user();
        $masjidId = $user->masjid_id;
        $tahun    = (int) $request->get('tahun', now()->year);

        $laporan = LaporanKeuanganMasjid::where('masjid_id', $masjidId)
            ->where('status', 'published')
            ->where('tahun', $tahun)
            ->orderBy('bulan')
            ->get();

        // Trend 6 bulan terakhir (published)
        $trendData = $this->getTrendData($masjidId);

        $availableYears = LaporanKeuanganMasjid::where('masjid_id', $masjidId)
            ->where('status', 'published')
            ->orderByDesc('tahun')
            ->distinct()
            ->pluck('tahun')
            ->toArray();

        return view('public.laporan-keuangan.index', compact(
            'laporan', 'tahun', 'availableYears', 'trendData'
        ));
    }

    // ================================================================
    // PUBLIC SHOW
    // ================================================================

    public function publicShow($uuid)
    {
        $laporan = LaporanKeuanganMasjid::where('uuid', $uuid)
            ->where('status', 'published')
            ->with(['masjid', 'creator'])
            ->firstOrFail();

        $periodeAwal  = $laporan->periode_mulai;
        $periodeAkhir = $laporan->periode_selesai;
        $masjidId     = $laporan->masjid_id;

        $detailPenerimaan = $this->getDetailPenerimaan($masjidId, $periodeAwal, $periodeAkhir);
        $detailPenyaluran = $this->getDetailPenyaluran($masjidId, $periodeAwal, $periodeAkhir);

        $colors = ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6', '#F97316'];

        $chartPenerimaan = [
            'labels'          => array_column($detailPenerimaan, 'jenis_zakat'),
            'data'            => array_map('floatval', array_column($detailPenerimaan, 'jumlah')),
            'backgroundColor' => array_slice($colors, 0, count($detailPenerimaan)),
        ];

        $chartPenyaluran = [
            'labels'          => array_column($detailPenyaluran, 'kategori'),
            'data'            => array_map('floatval', array_column($detailPenyaluran, 'jumlah')),
            'backgroundColor' => array_slice($colors, 0, count($detailPenyaluran)),
        ];

        return view('public.laporan-keuangan.show', compact(
            'laporan', 'detailPenerimaan', 'detailPenyaluran',
            'chartPenerimaan', 'chartPenyaluran'
        ));
    }

    // ================================================================
    // DOWNLOAD PDF — Laporan Bulanan
    // ================================================================

    public function downloadPDF($uuid)
    {
        $user    = Auth::user();
        $laporan = LaporanKeuanganMasjid::where('uuid', $uuid)
            ->where('masjid_id', $user->masjid_id)
            ->with(['masjid', 'creator'])
            ->firstOrFail();

        $periodeAwal  = $laporan->periode_mulai;
        $periodeAkhir = $laporan->periode_selesai;
        $masjidId     = $laporan->masjid_id;

        $detailPenerimaan = $this->getDetailPenerimaan($masjidId, $periodeAwal, $periodeAkhir);
        $detailPenyaluran = $this->getDetailPenyaluran($masjidId, $periodeAwal, $periodeAkhir);

        $pdf = Pdf::loadView('admin-masjid.laporan-keuangan.pdf', [
            'laporan'          => $laporan,
            'detailPenerimaan' => $detailPenerimaan,
            'detailPenyaluran' => $detailPenyaluran,
            'logo'             => $this->getLogoBase64(),
            'tanggalCetak'     => now()->format('d F Y H:i:s'),
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('defaultFont', 'Helvetica');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', false);

        $filename = 'Laporan-Keuangan-' . $laporan->masjid->nama . '-' . $laporan->tahun . '-' . str_pad($laporan->bulan, 2, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }

    // ================================================================
    // DOWNLOAD PDF — Laporan Tahunan
    // ================================================================

    public function downloadTahunanPDF(Request $request, $tahun)
    {
        $user     = Auth::user();
        $masjidId = $user->masjid_id;

        $laporanTahunan = LaporanKeuanganMasjid::where('masjid_id', $masjidId)
            ->where('tahun', $tahun)
            ->orderBy('bulan')
            ->get();

        if ($laporanTahunan->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data laporan untuk tahun ' . $tahun . '.');
        }

        // Hitung ringkasan dari transaksi langsung
        $periodeAwal  = Carbon::createFromDate($tahun, 1, 1)->startOfYear();
        $periodeAkhir = Carbon::createFromDate($tahun, 12, 31)->endOfYear();

        $totalPenerimaan = TransaksiPenerimaan::where('masjid_id', $masjidId)
            ->where('status', 'verified')
            ->whereBetween('tanggal_transaksi', [$periodeAwal, $periodeAkhir])
            ->sum('jumlah');

        $totalPenyaluran = TransaksiPenyaluran::where('masjid_id', $masjidId)
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->whereBetween('tanggal_penyaluran', [$periodeAwal, $periodeAkhir])
            ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END'));

        $totalMuzakki  = $laporanTahunan->sum('jumlah_muzakki');
        $totalMustahik = $laporanTahunan->sum('jumlah_mustahik');
        $masjid        = $laporanTahunan->first()->masjid;

        $pdf = Pdf::loadView('admin-masjid.laporan-keuangan.pdf-tahunan', [
            'laporanTahunan'  => $laporanTahunan,
            'masjid'          => $masjid,
            'tahun'           => $tahun,
            'totalPenerimaan' => $totalPenerimaan,
            'totalPenyaluran' => $totalPenyaluran,
            'totalMuzakki'    => $totalMuzakki,
            'totalMustahik'   => $totalMustahik,
            'logo'            => $this->getLogoBase64(),
            'tanggalCetak'    => now()->format('d F Y H:i:s'),
        ]);

        $pdf->setPaper('A4', 'landscape');
        $pdf->setOption('defaultFont', 'Helvetica');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', false);

        return $pdf->download('Laporan-Tahunan-' . $masjid->nama . '-' . $tahun . '.pdf');
    }

    // ================================================================
    // DOWNLOAD PDF — Public (Published Only)
    // ================================================================

    public function downloadPublicPDF($uuid)
    {
        $laporan = LaporanKeuanganMasjid::where('uuid', $uuid)
            ->where('status', 'published')
            ->with(['masjid', 'creator'])
            ->firstOrFail();

        $periodeAwal  = $laporan->periode_mulai;
        $periodeAkhir = $laporan->periode_selesai;
        $masjidId     = $laporan->masjid_id;

        $detailPenerimaan = $this->getDetailPenerimaan($masjidId, $periodeAwal, $periodeAkhir);
        $detailPenyaluran = $this->getDetailPenyaluran($masjidId, $periodeAwal, $periodeAkhir);

        $pdf = Pdf::loadView('public.laporan-keuangan.pdf', [
            'laporan'          => $laporan,
            'detailPenerimaan' => $detailPenerimaan,
            'detailPenyaluran' => $detailPenyaluran,
            'logo'             => $this->getLogoBase64(),
            'tanggalCetak'     => now()->format('d F Y H:i:s'),
            'isPublic'         => true,
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('defaultFont', 'Helvetica');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', false);

        return $pdf->download('Laporan-Publik-' . $laporan->masjid->nama . '-' . $laporan->tahun . '-' . str_pad($laporan->bulan, 2, '0', STR_PAD_LEFT) . '.pdf');
    }

    // ================================================================
    // PRIVATE HELPERS
    // ================================================================

    /**
     * Ambil detail penerimaan per jenis zakat dalam periode tertentu
     */
    private function getDetailPenerimaan(int $masjidId, $periodeAwal, $periodeAkhir): array
    {
        return TransaksiPenerimaan::where('transaksi_penerimaan.masjid_id', $masjidId)
            ->where('transaksi_penerimaan.status', 'verified')
            ->whereBetween('transaksi_penerimaan.tanggal_transaksi', [$periodeAwal, $periodeAkhir])
            ->leftJoin('jenis_zakat', 'transaksi_penerimaan.jenis_zakat_id', '=', 'jenis_zakat.id')
            ->select(
                DB::raw('COALESCE(jenis_zakat.nama, "Lainnya") as jenis_zakat'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(transaksi_penerimaan.jumlah) as jumlah')
            )
            ->groupBy('jenis_zakat.id', 'jenis_zakat.nama')
            ->orderByDesc('jumlah')
            ->get()
            ->toArray();
    }

    /**
     * Ambil detail penyaluran per kategori mustahik dalam periode tertentu
     */
    private function getDetailPenyaluran(int $masjidId, $periodeAwal, $periodeAkhir): array
    {
        return TransaksiPenyaluran::where('transaksi_penyaluran.masjid_id', $masjidId)
            ->whereIn('transaksi_penyaluran.status', ['disetujui', 'disalurkan'])
            ->whereBetween('transaksi_penyaluran.tanggal_penyaluran', [$periodeAwal, $periodeAkhir])
            ->leftJoin('kategori_mustahik', 'transaksi_penyaluran.kategori_mustahik_id', '=', 'kategori_mustahik.id')
            ->select(
                DB::raw('COALESCE(kategori_mustahik.nama, "Lainnya") as kategori'),
                DB::raw('COUNT(DISTINCT transaksi_penyaluran.mustahik_id) as count'),
                DB::raw('SUM(CASE WHEN transaksi_penyaluran.metode_penyaluran = "barang" THEN COALESCE(transaksi_penyaluran.nilai_barang, 0) ELSE transaksi_penyaluran.jumlah END) as jumlah')
            )
            ->groupBy('kategori_mustahik.id', 'kategori_mustahik.nama')
            ->orderByDesc('jumlah')
            ->get()
            ->toArray();
    }

    /**
     * Data trend 6 bulan terakhir untuk halaman publik
     */
    private function getTrendData(int $masjidId): array
    {
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();

        $rows = LaporanKeuanganMasjid::where('masjid_id', $masjidId)
            ->where('status', 'published')
            ->where('periode_mulai', '>=', $sixMonthsAgo)
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        return [
            'labels'     => $rows->map(fn($r) => $r->nama_bulan . ' ' . $r->tahun)->toArray(),
            'penerimaan' => $rows->map(fn($r) => (float) $r->total_penerimaan)->toArray(),
            'penyaluran' => $rows->map(fn($r) => (float) $r->total_penyaluran)->toArray(),
        ];
    }

    /**
     * Logo masjid sebagai base64 untuk PDF
     */
    private function getLogoBase64(): ?string
    {
        try {
            $logoPath = public_path('images/logo-masjid.png');
            if (file_exists($logoPath)) {
                $ext  = pathinfo($logoPath, PATHINFO_EXTENSION);
                $data = file_get_contents($logoPath);
                return 'data:image/' . $ext . ';base64,' . base64_encode($data);
            }
        } catch (\Exception $e) {
            // silent
        }

        return null;
    }
}