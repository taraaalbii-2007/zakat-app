<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Lembaga;
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
            case 'admin_lembaga':
                return $this->adminLembagaDashboard();
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
        $breadcrumbs = [
            'Dashboard Superadmin' => null,
        ];
        $stats = [
            'total_lembaga'            => Lembaga::count(),
            'lembaga_aktif'            => Lembaga::where('is_active', true)->count(),
            'total_pengguna'          => Pengguna::count(),
            'total_admin_lembaga'      => Pengguna::where('peran', 'admin_lembaga')->count(),
            'total_amil'              => Pengguna::where('peran', 'amil')->count(),
            'total_jenis_zakat'       => JenisZakat::count(),
            'total_kategori_mustahik' => KategoriMustahik::count(),
            'harga_emas_terkini'      => HargaEmasPerak::where('is_active', true)->latest('tanggal')->first(),
        ];

        $lembagaTerbaru   = Lembaga::latest()->take(20)->get();
        $penggunaTerbaru = Pengguna::latest()->take(20)->get();

        $lembagaPerProvinsi = Lembaga::select('provinsi_nama', DB::raw('count(*) as jumlah'))
            ->whereNotNull('provinsi_nama')
            ->groupBy('provinsi_nama')
            ->orderByDesc('jumlah')
            ->take(5)
            ->get()
            ->map(fn($item) => ['nama' => $item->provinsi_nama, 'jumlah' => $item->jumlah]);

        $trendLembaga = Lembaga::select(
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
            'lembagaTerbaru',
            'penggunaTerbaru',
            'lembagaPerProvinsi',
            'trendLembaga',
            'logTerbaru'
        ));
    }

    // ================================================================
    // ADMIN MASJID DASHBOARD
    // ================================================================

    protected function adminLembagaDashboard()
    {
        $user    = Auth::user();
        $lembaga = $user->lembaga;

        // ── Pengecekan: admin belum terhubung ke lembaga ─────────────────────
        if (!$lembaga) {
            // Logout user agar tidak loop
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Akun Anda belum terhubung ke lembaga manapun. Hubungi superadmin.');
        }

        $lembagaId    = $lembaga->id;
        $breadcrumbs  = [['name' => 'Dashboard Admin Lembaga', 'url' => null]];
        $periodeAwal  = now()->startOfMonth();
        $periodeAkhir = now()->endOfMonth();

        // ── Keuangan Bulan Ini ───────────────────────────────────────────────
        $totalPenerimaanBulanIni = TransaksiPenerimaan::where('lembaga_id', $lembagaId)
            ->where('status', 'verified')
            ->whereBetween('tanggal_transaksi', [$periodeAwal, $periodeAkhir])
            ->sum('jumlah');

        $totalPenyaluranBulanIni = TransaksiPenyaluran::where('lembaga_id', $lembagaId)
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->whereBetween('tanggal_penyaluran', [$periodeAwal, $periodeAkhir])
            ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END'));

        // Saldo = all-time penerimaan verified - all-time penyaluran
        $saldoZakat = TransaksiPenerimaan::where('lembaga_id', $lembagaId)
            ->where('status', 'verified')
            ->sum('jumlah')
            -
            TransaksiPenyaluran::where('lembaga_id', $lembagaId)
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END'));

        // ── SDM ──────────────────────────────────────────────────────────────
        $totalAmil = Amil::where('lembaga_id', $lembagaId)
            ->where('status', 'aktif')
            ->count();

        $jumlahMuzakki = TransaksiPenerimaan::where('lembaga_id', $lembagaId)
            ->where('status', 'verified')
            ->distinct('muzakki_nama')
            ->count('muzakki_nama');

        $jumlahMustahik = Mustahik::where('lembaga_id', $lembagaId)
            ->where('status_verifikasi', 'verified')
            ->where('is_active', true)
            ->count();

        $stats = [
            'nama_lembaga'               => $lembaga->nama,
            'kode_lembaga'               => $lembaga->kode_lembaga,
            'total_amil'                 => $totalAmil,
            'total_penerimaan_bulan_ini' => $totalPenerimaanBulanIni,
            'total_penyaluran_bulan_ini' => $totalPenyaluranBulanIni,
            'saldo_zakat'                => $saldoZakat,
            'jumlah_muzakki'             => $jumlahMuzakki,
            'jumlah_mustahik'            => $jumlahMustahik,
        ];

        // ── Trend 6 Bulan (Penerimaan & Penyaluran) ─────────────────────────
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();

        $penerimaanPerBulan = TransaksiPenerimaan::where('lembaga_id', $lembagaId)
            ->where('status', 'verified')
            ->where('tanggal_transaksi', '>=', $sixMonthsAgo)
            ->selectRaw('DATE_FORMAT(tanggal_transaksi, "%Y-%m") as ym, SUM(jumlah) as total')
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $penyaluranPerBulan = TransaksiPenyaluran::where('lembaga_id', $lembagaId)
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

        $breadcrumbs = [
            'Dashboard Admin Lembaga' => null,
        ];

        // ── Approval Penyaluran (draft menunggu persetujuan) ─────────────────
        $penyaluranPendingApproval = TransaksiPenyaluran::where('lembaga_id', $lembagaId)
            ->where('status', 'draft')
            ->with(['mustahik', 'kategoriMustahik', 'amil'])
            ->latest()
            ->take(10)
            ->get();

        $totalPendingApproval = TransaksiPenyaluran::where('lembaga_id', $lembagaId)
            ->where('status', 'draft')
            ->count();

        $totalNominalPending = TransaksiPenyaluran::where('lembaga_id', $lembagaId)
            ->where('status', 'draft')
            ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END'));

        // ── Master Data & Lainnya ─────────────────────────────────────────────
        $hargaTerkini     = HargaEmasPerak::where('is_active', true)->latest('tanggal')->first();
        $jenisZakatAktif  = JenisZakat::all();
        $kategoriMustahik = KategoriMustahik::all();
        $logAktivitas     = LogAktivitas::where('pengguna_id', $user->id)->latest()->take(5)->get();

        return view('dashboard.admin_lembaga', compact(
            'breadcrumbs',
            'user',
            'lembaga',
            'stats',
            'hargaTerkini',
            'jenisZakatAktif',
            'kategoriMustahik',
            'trendPenerimaan',
            'logAktivitas',
            'penyaluranPendingApproval',
            'totalPendingApproval',
            'totalNominalPending',
            'breadcrumbs'
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

        if (!$amil || !$amil->lembaga) {
            return redirect()->route('dashboard')
                ->with('warning', 'Anda belum terdaftar sebagai amil aktif di lembaga manapun.');
        }

        $lembaga      = $amil->lembaga;
        $lembagaId    = $lembaga->id;
        $breadcrumbs = [
            'Dashboard Amil' => null,
        ];
        $periodeAwal  = now()->startOfMonth();
        $periodeAkhir = now()->endOfMonth();

        // ── Keuangan Bulan Ini ───────────────────────────────────────────────
        $totalPenerimaanBulanIni = TransaksiPenerimaan::where('lembaga_id', $lembagaId)
            ->where('status', 'verified')
            ->whereBetween('tanggal_transaksi', [$periodeAwal, $periodeAkhir])
            ->sum('jumlah');

        $totalPenyaluranBulanIni = TransaksiPenyaluran::where('lembaga_id', $lembagaId)
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->whereBetween('tanggal_penyaluran', [$periodeAwal, $periodeAkhir])
            ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END'));

        $saldoSaatIni = TransaksiPenerimaan::where('lembaga_id', $lembagaId)
            ->where('status', 'verified')
            ->sum('jumlah')
            -
            TransaksiPenyaluran::where('lembaga_id', $lembagaId)
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END'));

        // ── SDM ──────────────────────────────────────────────────────────────
        $jumlahMuzakki = TransaksiPenerimaan::where('lembaga_id', $lembagaId)
            ->where('status', 'verified')
            ->distinct('muzakki_nama')
            ->count('muzakki_nama');

        $jumlahMustahik = Mustahik::where('lembaga_id', $lembagaId)
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

            $penerimaan = TransaksiPenerimaan::where('lembaga_id', $lembagaId)
                ->where('status', 'verified')
                ->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])
                ->sum('jumlah');

            $penyaluran = TransaksiPenyaluran::where('lembaga_id', $lembagaId)
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
            'lembaga',
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
            ->with(['lembaga', 'transaksiPenerimaan'])
            ->first();

        if (!$muzakki) {
            return redirect()->route('login')
                ->with('error', 'Data muzakki tidak ditemukan. Silakan hubungi administrator.');
        }

        $lembaga      = $muzakki->lembaga;
        $lembagaId    = $lembaga?->id;
        $breadcrumbs = [
            'Dashboard Muzakki' => null,
        ];
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
            'nama_lembaga'         => $lembaga?->nama ?? '-',
            'total_zakat_dibayar' => $totalZakatDibayar,
            'total_transaksi'     => $totalTransaksi,
            'transaksi_pending'   => $transaksiPending,
            'zakat_tahun_ini'     => $transaksiTahunIni,
        ];

        // ── Statistik Transparansi Lembaga ──────────────────────────────────
        $statsLembaga = [];
        if ($lembagaId) {
            $totalPenerimaanLembaga  = TransaksiPenerimaan::byLembaga($lembagaId)->verified()->sum('jumlah');
            $totalPenyaluranLembaga  = TransaksiPenyaluran::byLembaga($lembagaId)
                ->whereIn('status', ['disetujui', 'disalurkan'])
                ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END'));

            $statsLembaga = [
                'total_penerimaan'     => $totalPenerimaanLembaga,
                'total_penyaluran'     => $totalPenyaluranLembaga,
                'saldo_kas'            => $totalPenerimaanLembaga - $totalPenyaluranLembaga,
                'rasio_penyaluran'     => $totalPenerimaanLembaga > 0
                    ? round(($totalPenyaluranLembaga / $totalPenerimaanLembaga) * 100, 1)
                    : 0,
                'penerimaan_bulan_ini' => TransaksiPenerimaan::byLembaga($lembagaId)->verified()
                    ->whereYear('tanggal_transaksi', now()->year)
                    ->whereMonth('tanggal_transaksi', now()->month)
                    ->sum('jumlah'),
                'penyaluran_bulan_ini' => TransaksiPenyaluran::byLembaga($lembagaId)
                    ->whereIn('status', ['disetujui', 'disalurkan'])
                    ->whereYear('tanggal_penyaluran', now()->year)
                    ->whereMonth('tanggal_penyaluran', now()->month)
                    ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE jumlah END')),
                'total_muzakki'        => TransaksiPenerimaan::byLembaga($lembagaId)->verified()
                    ->whereNotNull('muzakki_id')->distinct('muzakki_id')->count('muzakki_id'),
                'total_mustahik'       => TransaksiPenyaluran::byLembaga($lembagaId)
                    ->whereIn('status', ['disetujui', 'disalurkan'])
                    ->distinct('mustahik_id')->count('mustahik_id'),
                'program_aktif'        => \App\Models\ProgramZakat::where('lembaga_id', $lembagaId)
                    ->where('status', 'aktif')->count(),
            ];
        }

        return view('dashboard.muzakki', compact(
            'breadcrumbs',
            'user',
            'muzakki',
            'lembaga',
            'stats',
            'riwayatTerbaru',
            'trendZakat',
            'hargaTerkini',
            'jenisZakatList',
            'statsLembaga'
        ));
    }
}