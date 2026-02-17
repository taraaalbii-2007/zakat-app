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

        // Cek apakah user ada
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
            'total_masjid' => Masjid::count(),
            'masjid_aktif' => Masjid::where('is_active', true)->count(),
            'total_pengguna' => Pengguna::count(),
            'total_admin_masjid' => Pengguna::where('peran', 'admin_masjid')->count(),
            'total_amil' => Pengguna::where('peran', 'amil')->count(),
            'total_jenis_zakat' => JenisZakat::count(),
            'total_kategori_mustahik' => KategoriMustahik::count(),
            'harga_emas_terkini' => HargaEmasPerak::where('is_active', true)->latest('tanggal')->first(),
        ];

        $masjidTerbaru = Masjid::latest()->take(5)->get();
        $penggunaTerbaru = Pengguna::latest()->take(5)->get();
        
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
                'bulan' => Carbon::create()->month($item->bulan)->translatedFormat('M') . ' ' . $item->tahun,
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
    $user = Auth::user();
    
    // ✅ Sekarang pakai relasi langsung
    $masjid = $user->masjid; // atau Masjid::find($user->masjid_id)

    // ✅ AKTIFKAN KEMBALI PENGEECEKAN INI!
    // if (!$masjid) {
    //     return redirect()->route('admin.konfigurasi.index')
    //         ->with('warning', 'Silakan lengkapi profil masjid Anda terlebih dahulu');
    // }

    $breadcrumbs = [['name' => 'Dashboard Admin Masjid', 'url' => null]];
    $bulanIni = now()->month;
    $tahunIni = now()->year;
    
    // ✅ KOMENTARI/PERBAIKI variabel yang belum didefinisikan
    // $laporanBulanIni = ViewLaporanKonsolidasi::where('masjid_id', $masjid->id)
    //     ->where('tahun', $tahunIni)
    //     ->where('bulan', $bulanIni)
    //     ->first();

    // ✅ PERBAIKAN: Inisialisasi variabel yang akan digunakan
    $laporanBulanIni = null; // atau query yang sesuai jika tabel sudah ada
    $total_amil = 0;

    $stats = [
        'nama_masjid' => $masjid->nama,
        'kode_masjid' => $masjid->kode_masjid,
        'total_amil' => $total_amil,
        'total_penerimaan_bulan_ini' => $laporanBulanIni->total_penerimaan ?? 0,
        'total_penyaluran_bulan_ini' => $laporanBulanIni->total_penyaluran ?? 0,
        'saldo_zakat' => $laporanBulanIni->saldo_akhir ?? 0,
        'jumlah_muzakki' => $laporanBulanIni->jumlah_muzakki ?? 0,
        'jumlah_mustahik' => $laporanBulanIni->jumlah_mustahik ?? 0,
    ];

    $hargaTerkini = HargaEmasPerak::where('is_active', true)->latest('tanggal')->first();
    $jenisZakatAktif = JenisZakat::all(); 
    $kategoriMustahik = KategoriMustahik::all();
    
    // ✅ KOMENTARI query yang menggunakan $masjid->id karena $laporanBulanIni mungkin null
    // $trendPenerimaan = ViewLaporanKonsolidasi::where('masjid_id', $masjid->id)
    //     ->where('tahun', '>=', Carbon::now()->subMonths(6)->year)
    //     ->orderBy('tahun')->orderBy('bulan')
    //     ->take(6)
    //     ->get()
    //     ->map(fn($item) => [
    //         'bulan' => Carbon::create()->month($item->bulan)->translatedFormat('M'),
    //         'penerimaan' => $item->total_penerimaan,
    //         'penyaluran' => $item->total_penyaluran,
    //     ]);

    $trendPenerimaan = collect(); // Berikan nilai default

    $logAktivitas = LogAktivitas::where('pengguna_id', $user->id)->latest()->take(5)->get();

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
    
    // ✅ Untuk amil: cari dari tabel amil
    $amil = \App\Models\Amil::where('pengguna_id', $user->id)
        ->where('status', 'aktif')
        ->first();

    if (!$amil || !$amil->masjid) {
        return redirect()->route('dashboard')
            ->with('warning', 'Anda belum terdaftar sebagai amil aktif di masjid manapun.');
    }

    $masjid = $amil->masjid;

        $breadcrumbs = [['name' => 'Dashboard Amil', 'url' => null]];
        $bulanIni = now()->month;
        $tahunIni = now()->year;
        
        $laporanBulanIni = ViewLaporanKonsolidasi::where('masjid_id', $masjid->id)
            ->where('tahun', $tahunIni)
            ->where('bulan', $bulanIni)
            ->first();

        $stats = [
            'nama_masjid' => $masjid->nama,
            'total_penerimaan_bulan_ini' => $laporanBulanIni->total_penerimaan ?? 0,
            'total_penyaluran_bulan_ini' => $laporanBulanIni->total_penyaluran ?? 0,
            'saldo_saat_ini' => $laporanBulanIni->saldo_akhir ?? 0,
            'jumlah_muzakki' => $laporanBulanIni->jumlah_muzakki ?? 0,
            'jumlah_mustahik' => $laporanBulanIni->jumlah_mustahik ?? 0,
        ];

        $hargaTerkini = HargaEmasPerak::where('is_active', true)->latest('tanggal')->first();
        $jenisZakat = JenisZakat::all();
        
        $quickStats = [
            ['label' => 'Muzakki Terdaftar', 'value' => $stats['jumlah_muzakki'], 'icon' => 'users'],
            ['label' => 'Mustahik Terdaftar', 'value' => $stats['jumlah_mustahik'], 'icon' => 'user-group'],
            ['label' => 'Saldo Zakat', 'value' => 'Rp ' . number_format($stats['saldo_saat_ini'], 0, ',', '.'), 'icon' => 'cash'],
        ];

        $reminders = [
            'Verifikasi muzakki baru', 
            'Update data mustahik', 
            'Laporan harian zakat', 
            'Cek stok formulir'
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