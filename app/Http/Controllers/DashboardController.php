<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Masjid;
use App\Models\JenisZakat;
use App\Models\KategoriMustahik;
use App\Models\HargaEmasPerak;
use App\Models\LogAktivitas;
use App\Models\ViewLaporanKonsolidasi;
use App\Models\Amil;
use App\Models\Mustahik;
use App\Models\TransaksiPenerimaan;
use App\Models\TransaksiPenyaluran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        switch ($user->peran) {
            case 'superadmin':
                return $this->superadminDashboard();
            case 'admin_masjid':
                return $this->adminMasjidDashboard();
            case 'amil':
                return $this->amilDashboard();
            case 'muzakki':                          // ← TAMBAH INI
                return $this->muzakkiDashboard();    // ← TAMBAH INI
            default:
                abort(403, 'Unauthorized access');
        }
    }

    // ================================================================
    // SUPERADMIN DASHBOARD
    // ================================================================

    protected function superadminDashboard()
    {
        $user        = Auth::user();
        $breadcrumbs = [['name' => 'Dashboard Superadmin', 'url' => null]];

        $stats = [
            'total_masjid'            => Masjid::count(),
            'masjid_aktif'            => Masjid::where('is_active', true)->count(),
            'total_pengguna'          => Pengguna::count(),
            'total_admin_masjid'      => Pengguna::where('peran', 'admin_masjid')->count(),
            'total_amil'              => Pengguna::where('peran', 'amil')->count(),
            'total_jenis_zakat'       => JenisZakat::count(),
            'total_kategori_mustahik' => KategoriMustahik::count(),
            'harga_emas_terkini'      => HargaEmasPerak::where('is_active', true)->latest('tanggal')->first(),
        ];

        $masjidTerbaru   = Masjid::latest()->take(20)->get();
        $penggunaTerbaru = Pengguna::latest()->take(20)->get();

        $masjidPerProvinsi = Masjid::select('provinsi_nama', DB::raw('count(*) as jumlah'))
            ->whereNotNull('provinsi_nama')
            ->groupBy('provinsi_nama')
            ->orderByDesc('jumlah')
            ->take(5)
            ->get()
            ->map(fn($item) => ['nama' => $item->provinsi_nama, 'jumlah' => $item->jumlah]);

        $trendMasjid = Masjid::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('YEAR(created_at) as tahun'),
            DB::raw('COUNT(*) as jumlah')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')->orderBy('bulan')
            ->get()
            ->map(fn($item) => [
                'bulan'  => Carbon::create()->month($item->bulan)->translatedFormat('M') . ' ' . $item->tahun,
                'jumlah' => $item->jumlah,
            ]);

        $logTerbaru = LogAktivitas::with('pengguna')->latest()->take(10)->get();

        return view('dashboard.superadmin', compact(
            'breadcrumbs',
            'user',
            'stats',
            'masjidTerbaru',
            'penggunaTerbaru',
            'masjidPerProvinsi',
            'trendMasjid',
            'logTerbaru'
        ));
    }

    // ================================================================
    // ADMIN MASJID DASHBOARD
    // ================================================================

    protected function adminMasjidDashboard()
    {
        $user     = Auth::user();
        $masjid   = $user->masjid;
        $masjidId = $masjid->id;

        $breadcrumbs  = [['name' => 'Dashboard Admin Masjid', 'url' => null]];
        $periodeAwal  = now()->startOfMonth();
        $periodeAkhir = now()->endOfMonth();

        // ── Keuangan Bulan Ini ───────────────────────────────────────────────
        $totalPenerimaanBulanIni = TransaksiPenerimaan::where('masjid_id', $masjidId)
            ->where('status', 'verified')
            ->whereBetween('tanggal_transaksi', [$periodeAwal, $periodeAkhir])
            ->sum('jumlah');

        $totalPenyaluranBulanIni = TransaksiPenyaluran::where('masjid_id', $masjidId)
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->whereBetween('tanggal_penyaluran', [$periodeAwal, $periodeAkhir])
            ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END'));

        // Saldo = all-time penerimaan verified - all-time penyaluran
        $saldoZakat = TransaksiPenerimaan::where('masjid_id', $masjidId)
            ->where('status', 'verified')
            ->sum('jumlah')
            -
            TransaksiPenyaluran::where('masjid_id', $masjidId)
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END'));

        // ── SDM ──────────────────────────────────────────────────────────────
        $totalAmil = Amil::where('masjid_id', $masjidId)
            ->where('status', 'aktif')
            ->count();

        $jumlahMuzakki = TransaksiPenerimaan::where('masjid_id', $masjidId)
            ->where('status', 'verified')
            ->distinct('muzakki_nama')
            ->count('muzakki_nama');

        $jumlahMustahik = Mustahik::where('masjid_id', $masjidId)
            ->where('status_verifikasi', 'verified')
            ->where('is_active', true)
            ->count();

        $stats = [
            'nama_masjid'                => $masjid->nama,
            'kode_masjid'                => $masjid->kode_masjid,
            'total_amil'                 => $totalAmil,
            'total_penerimaan_bulan_ini' => $totalPenerimaanBulanIni,
            'total_penyaluran_bulan_ini' => $totalPenyaluranBulanIni,
            'saldo_zakat'                => $saldoZakat,
            'jumlah_muzakki'             => $jumlahMuzakki,
            'jumlah_mustahik'            => $jumlahMustahik,
        ];

        // ── Trend 6 Bulan (Penerimaan & Penyaluran) ─────────────────────────
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();

        $penerimaanPerBulan = TransaksiPenerimaan::where('masjid_id', $masjidId)
            ->where('status', 'verified')
            ->where('tanggal_transaksi', '>=', $sixMonthsAgo)
            ->selectRaw('DATE_FORMAT(tanggal_transaksi, "%Y-%m") as ym, SUM(jumlah) as total')
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $penyaluranPerBulan = TransaksiPenyaluran::where('masjid_id', $masjidId)
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->where('tanggal_penyaluran', '>=', $sixMonthsAgo)
            ->selectRaw('DATE_FORMAT(tanggal_penyaluran, "%Y-%m") as ym,
                SUM(CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END) as total')
            ->groupBy('ym')
            ->pluck('total', 'ym');

        // Bangun 6 titik lengkap — label tetap ada meski data 0
        $trendPenerimaan = collect();
        for ($i = 5; $i >= 0; $i--) {
            $tgl = now()->subMonths($i)->startOfMonth();
            $key = $tgl->format('Y-m');
            $trendPenerimaan->push([
                'bulan'      => $tgl->translatedFormat('M Y'),
                'penerimaan' => (float) ($penerimaanPerBulan[$key] ?? 0),
                'penyaluran' => (float) ($penyaluranPerBulan[$key] ?? 0),
            ]);
        }

        // ── Approval Penyaluran (draft menunggu persetujuan) ─────────────────
        $penyaluranPendingApproval = TransaksiPenyaluran::where('masjid_id', $masjidId)
            ->where('status', 'draft')
            ->with(['mustahik', 'kategoriMustahik', 'amil'])
            ->latest()
            ->take(10)
            ->get();

        $totalPendingApproval = TransaksiPenyaluran::where('masjid_id', $masjidId)
            ->where('status', 'draft')
            ->count();

        $totalNominalPending = TransaksiPenyaluran::where('masjid_id', $masjidId)
            ->where('status', 'draft')
            ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END'));

        // ── Master Data & Lainnya ─────────────────────────────────────────────
        $hargaTerkini     = HargaEmasPerak::where('is_active', true)->latest('tanggal')->first();
        $jenisZakatAktif  = JenisZakat::all();
        $kategoriMustahik = KategoriMustahik::all();
        $logAktivitas     = LogAktivitas::where('pengguna_id', $user->id)->latest()->take(5)->get();

        return view('dashboard.admin_masjid', compact(
            'breadcrumbs',
            'user',
            'masjid',
            'stats',
            'hargaTerkini',
            'jenisZakatAktif',
            'kategoriMustahik',
            'trendPenerimaan',
            'logAktivitas',
            'penyaluranPendingApproval',
            'totalPendingApproval',
            'totalNominalPending'
        ));
    }

    // ================================================================
    // AMIL DASHBOARD
    // ================================================================

    protected function amilDashboard()
    {
        $user = Auth::user();

        $amil = Amil::where('pengguna_id', $user->id)
            ->where('status', 'aktif')
            ->first();

        if (!$amil || !$amil->masjid) {
            return redirect()->route('dashboard')
                ->with('warning', 'Anda belum terdaftar sebagai amil aktif di masjid manapun.');
        }

        $masjid      = $amil->masjid;
        $masjidId    = $masjid->id;
        $breadcrumbs = [['name' => 'Dashboard Amil', 'url' => null]];

        $periodeAwal  = now()->startOfMonth();
        $periodeAkhir = now()->endOfMonth();

        // ── Keuangan Bulan Ini ───────────────────────────────────────────────
        $totalPenerimaanBulanIni = TransaksiPenerimaan::where('masjid_id', $masjidId)
            ->where('status', 'verified')
            ->whereBetween('tanggal_transaksi', [$periodeAwal, $periodeAkhir])
            ->sum('jumlah');

        $totalPenyaluranBulanIni = TransaksiPenyaluran::where('masjid_id', $masjidId)
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->whereBetween('tanggal_penyaluran', [$periodeAwal, $periodeAkhir])
            ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END'));

        $saldoSaatIni = TransaksiPenerimaan::where('masjid_id', $masjidId)
            ->where('status', 'verified')
            ->sum('jumlah')
            -
            TransaksiPenyaluran::where('masjid_id', $masjidId)
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END'));

        // ── SDM ──────────────────────────────────────────────────────────────
        $jumlahMuzakki = TransaksiPenerimaan::where('masjid_id', $masjidId)
            ->where('status', 'verified')
            ->distinct('muzakki_nama')
            ->count('muzakki_nama');

        $jumlahMustahik = Mustahik::where('masjid_id', $masjidId)
            ->where('status_verifikasi', 'verified')
            ->where('is_active', true)
            ->count();

        // ── Statistik utama ──────────────────────────────────────────────────
        $stats = [
            'total_penerimaan_bulan_ini' => $totalPenerimaanBulanIni,
            'total_penyaluran_bulan_ini' => $totalPenyaluranBulanIni,
            'saldo_saat_ini'             => $saldoSaatIni,
        ];

        $quickStats = [
            [
                'label' => 'Muzakki Aktif',
                'value' => $jumlahMuzakki,
                'icon'  => 'users',
            ],
            [
                'label' => 'Mustahik Terdaftar',
                'value' => $jumlahMustahik,
                'icon'  => 'user-group',
            ],
        ];

        // ── Trend 6 Bulan ────────────────────────────────────────────────────
        $trendData = collect();
        for ($i = 5; $i >= 0; $i--) {
            $bulan        = now()->subMonths($i);
            $startOfMonth = $bulan->copy()->startOfMonth();
            $endOfMonth   = $bulan->copy()->endOfMonth();

            $penerimaan = TransaksiPenerimaan::where('masjid_id', $masjidId)
                ->where('status', 'verified')
                ->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])
                ->sum('jumlah');

            $penyaluran = TransaksiPenyaluran::where('masjid_id', $masjidId)
                ->whereIn('status', ['disetujui', 'disalurkan'])
                ->whereBetween('tanggal_penyaluran', [$startOfMonth, $endOfMonth])
                ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END'));

            $trendData->push([
                'bulan'      => $bulan->translatedFormat('M Y'),
                'penerimaan' => (float) $penerimaan,
                'penyaluran' => (float) $penyaluran,
            ]);
        }

        return view('dashboard.amil', compact(
            'breadcrumbs',
            'user',
            'amil',
            'masjid',
            'stats',
            'quickStats',
            'trendData'
        ));
    }

    protected function muzakkiDashboard()
    {
        $user = Auth::user();

        // Ambil data muzakki milik user ini
        $muzakki = \App\Models\Muzakki::where('pengguna_id', $user->id)
            ->with(['masjid', 'transaksiPenerimaan'])
            ->first();

        if (!$muzakki) {
            return redirect()->route('login')
                ->with('error', 'Data muzakki tidak ditemukan. Silakan hubungi administrator.');
        }

        $masjid      = $muzakki->masjid;
        $masjidId    = $masjid?->id;
        $breadcrumbs = [['name' => 'Dashboard Muzakki', 'url' => null]];

        // ── Statistik transaksi muzakki ──────────────────────────────────────
        $totalZakatDibayar = TransaksiPenerimaan::where('muzakki_id', $muzakki->id)
            ->where('status', 'verified')
            ->sum('jumlah');

        $totalTransaksi = TransaksiPenerimaan::where('muzakki_id', $muzakki->id)
            ->count();

        $transaksiPending = TransaksiPenerimaan::where('muzakki_id', $muzakki->id)
            ->whereIn('status', ['pending', 'menunggu_konfirmasi'])
            ->count();

        $transaksiTahunIni = TransaksiPenerimaan::where('muzakki_id', $muzakki->id)
            ->where('status', 'verified')
            ->whereYear('tanggal_transaksi', now()->year)
            ->sum('jumlah');

        // ── Riwayat transaksi terbaru (5 terakhir) ───────────────────────────
        $riwayatTerbaru = TransaksiPenerimaan::where('muzakki_id', $muzakki->id)
            ->with(['jenisZakat'])
            ->latest('tanggal_transaksi')
            ->take(5)
            ->get();

        // ── Trend 6 bulan terakhir ───────────────────────────────────────────
        $trendZakat = collect();
        for ($i = 5; $i >= 0; $i--) {
            $bulan        = now()->subMonths($i);
            $startOfMonth = $bulan->copy()->startOfMonth();
            $endOfMonth   = $bulan->copy()->endOfMonth();

            $jumlah = TransaksiPenerimaan::where('muzakki_id', $muzakki->id)
                ->where('status', 'verified')
                ->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])
                ->sum('jumlah');

            $trendZakat->push([
                'bulan'  => $bulan->translatedFormat('M Y'),
                'jumlah' => (float) $jumlah,
            ]);
        }

        // ── Harga emas terkini (untuk info nisab) ───────────────────────────
        $hargaTerkini = \App\Models\HargaEmasPerak::where('is_active', true)
            ->latest('tanggal')
            ->first();

        // ── Jenis zakat yang tersedia ────────────────────────────────────────
        $jenisZakatList = \App\Models\JenisZakat::all();

        $stats = [
            'nama_muzakki'        => $muzakki->nama,
            'nama_masjid'         => $masjid?->nama ?? '-',
            'total_zakat_dibayar' => $totalZakatDibayar,
            'total_transaksi'     => $totalTransaksi,
            'transaksi_pending'   => $transaksiPending,
            'zakat_tahun_ini'     => $transaksiTahunIni,
        ];

        return view('dashboard.muzakki', compact(
            'breadcrumbs',
            'user',
            'muzakki',
            'masjid',
            'stats',
            'riwayatTerbaru',
            'trendZakat',
            'hargaTerkini',
            'jenisZakatList'
        ));
    }
}