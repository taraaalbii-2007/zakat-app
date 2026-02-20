<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Masjid;
use App\Models\JenisZakat;
use App\Models\KategoriMustahik;
use App\Models\HargaEmasPerak;
use App\Models\LogAktivitas;
use App\Models\ViewLaporanKonsolidasi;
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
            default:
                abort(403, 'Unauthorized access');
        }
    }

    protected function superadminDashboard()
    {
        $user = Auth::user();
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

        // Ambil 20 data agar pagination client-side bisa berjalan (5 per halaman = 4 halaman)
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

    protected function adminMasjidDashboard()
    {
        $user  = Auth::user();
        $masjid = $user->masjid;

        $breadcrumbs = [['name' => 'Dashboard Admin Masjid', 'url' => null]];
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        $laporanBulanIni = null;
        $total_amil      = 0;

        $stats = [
            'nama_masjid'                 => $masjid->nama,
            'kode_masjid'                 => $masjid->kode_masjid,
            'total_amil'                  => $total_amil,
            'total_penerimaan_bulan_ini'  => $laporanBulanIni->total_penerimaan ?? 0,
            'total_penyaluran_bulan_ini'  => $laporanBulanIni->total_penyaluran ?? 0,
            'saldo_zakat'                 => $laporanBulanIni->saldo_akhir ?? 0,
            'jumlah_muzakki'              => $laporanBulanIni->jumlah_muzakki ?? 0,
            'jumlah_mustahik'             => $laporanBulanIni->jumlah_mustahik ?? 0,
        ];

        $hargaTerkini    = HargaEmasPerak::where('is_active', true)->latest('tanggal')->first();
        $jenisZakatAktif = JenisZakat::all();
        $kategoriMustahik = KategoriMustahik::all();
        $trendPenerimaan = collect();
        $logAktivitas    = LogAktivitas::where('pengguna_id', $user->id)->latest()->take(5)->get();

        return view('dashboard.admin_masjid', compact(
            'breadcrumbs',
            'user',
            'masjid',
            'stats',
            'hargaTerkini',
            'jenisZakatAktif',
            'kategoriMustahik',
            'trendPenerimaan',
            'logAktivitas'
        ));
    }

    protected function amilDashboard()
    {
        $user = Auth::user();

        $amil = \App\Models\Amil::where('pengguna_id', $user->id)
            ->where('status', 'aktif')
            ->first();

        if (!$amil || !$amil->masjid) {
            return redirect()->route('dashboard')
                ->with('warning', 'Anda belum terdaftar sebagai amil aktif di masjid manapun.');
        }

        $masjid       = $amil->masjid;
        $breadcrumbs  = [['name' => 'Dashboard Amil', 'url' => null]];
        $bulanIni     = now()->month;
        $tahunIni     = now()->year;

        $laporanBulanIni = ViewLaporanKonsolidasi::where('masjid_id', $masjid->id)
            ->where('tahun', $tahunIni)
            ->where('bulan', $bulanIni)
            ->first();

        $stats = [
            'nama_masjid'                => $masjid->nama,
            'total_penerimaan_bulan_ini' => $laporanBulanIni->total_penerimaan ?? 0,
            'total_penyaluran_bulan_ini' => $laporanBulanIni->total_penyaluran ?? 0,
            'saldo_saat_ini'             => $laporanBulanIni->saldo_akhir ?? 0,
            'jumlah_muzakki'             => $laporanBulanIni->jumlah_muzakki ?? 0,
            'jumlah_mustahik'            => $laporanBulanIni->jumlah_mustahik ?? 0,
        ];

        $hargaTerkini = HargaEmasPerak::where('is_active', true)->latest('tanggal')->first();
        $jenisZakat   = JenisZakat::all();

        $quickStats = [
            ['label' => 'Muzakki Terdaftar',  'value' => $stats['jumlah_muzakki'],  'icon' => 'users'],
            ['label' => 'Mustahik Terdaftar', 'value' => $stats['jumlah_mustahik'], 'icon' => 'user-group'],
            ['label' => 'Saldo Zakat',        'value' => 'Rp ' . number_format($stats['saldo_saat_ini'], 0, ',', '.'), 'icon' => 'cash'],
        ];

        $reminders = [
            'Verifikasi muzakki baru',
            'Update data mustahik',
            'Laporan harian zakat',
            'Cek stok formulir',
        ];

        return view('dashboard.amil', compact(
            'breadcrumbs',
            'user',
            'masjid',
            'stats',
            'hargaTerkini',
            'jenisZakat',
            'quickStats',
            'reminders'
        ));
    }
}