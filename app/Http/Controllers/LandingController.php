<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HargaEmasPerak;
use App\Models\JenisZakat;
use App\Models\Mustahik;
use App\Models\Bulletin;
use App\Models\Lembaga;
use App\Models\KategoriBulletin;
use App\Models\ProgramZakat;
use App\Models\TransaksiPenyaluran;
use App\Models\Testimoni;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        $hargaTerbaru = HargaEmasPerak::where('is_active', true)
            ->orderBy('tanggal', 'desc')
            ->first();

        $jenisZakat = JenisZakat::orderBy('nama')->get();

        $totalMuzaki = DB::table('transaksi_penerimaan')
            ->whereNotNull('muzakki_nama')
            ->selectRaw('COUNT(DISTINCT CONCAT(muzakki_nama, "-", lembaga_id)) as cnt')
            ->value('cnt') ?? 0;

        $totalMustahik = Mustahik::count();

        $totalDana = TransaksiPenyaluran::whereIn('status', ['disalurkan', 'disetujui'])
            ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE COALESCE(jumlah, 0) END'));

        $totalProgram = ProgramZakat::count();

        $totalLembaga = Lembaga::where('is_active', true)->count();

        $testimonis = Testimoni::where('is_approved', true)
            ->orderBy('approved_at', 'desc')
            ->limit(6)
            ->get();

        // Ambil beberapa bulletin terbaru untuk ditampilkan di landing page
        // ← PERBAIKAN: tambah eager load 'author' & 'lembaga'
        $bulletinTerbaru = Bulletin::with(['kategoriBulletin', 'author', 'lembaga'])
            ->published()
            ->latest('published_at')
            ->limit(3)
            ->get();
        
        $laporanPublished = \App\Models\LaporanKeuanganLembaga::with('lembaga')
        ->where('status', 'published')
        ->orderBy('published_at', 'desc')
        ->limit(3) 
        ->get();

        return view('layouts.guest', compact(
            'hargaTerbaru',
            'jenisZakat',
            'totalMuzaki',
            'totalMustahik',
            'totalDana',
            'totalProgram',
            'totalLembaga',
            'testimonis',
            'bulletinTerbaru',
            'laporanPublished'
        ));
    }

    public function hitungZakat()
    {
        $hargaTerbaru = HargaEmasPerak::where('is_active', true)
            ->orderBy('tanggal', 'desc')
            ->first();

        $hargaEmasPerGram = ($hargaTerbaru && $hargaTerbaru->harga_emas_pergram > 0)
            ? (int) $hargaTerbaru->harga_emas_pergram
            : 1900000;

        $nisabMaal    = $hargaEmasPerGram * 85;
        $nisabBulanan = (int) round($nisabMaal / 12);

        return view('pages.hitung-zakat', compact(
            'hargaTerbaru',
            'hargaEmasPerGram',
            'nisabMaal',
            'nisabBulanan'
        ));
    }

    public function panduanZakat()
    {
        return view('pages.panduan-zakat');
    }

    public function artikel(Request $request)
    {
        // ← PERBAIKAN: tambah eager load 'lembaga'
        $query = Bulletin::with(['kategoriBulletin', 'author', 'lembaga'])
            ->published()
            ->latest('published_at');

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        if ($request->filled('kategori')) {
            $query->byKategori($request->kategori);
        }

        $bulletins    = $query->paginate(9)->withQueryString();
        $kategoriList = KategoriBulletin::orderBy('nama_kategori')->get();

        return view('pages.bulletin', compact('bulletins', 'kategoriList'));
    }

    public function artikelShow($slug)
    {
        // ← PERBAIKAN: tambah eager load 'lembaga'
        $bulletin = Bulletin::with(['kategoriBulletin', 'author', 'lembaga'])
            ->where('slug', $slug)
            ->where('status', 'approved')
            ->firstOrFail();

        $bulletin->incrementViewCount();

        // Artikel terkait: sekategori dulu
        // ← PERBAIKAN: tambah eager load 'lembaga'
        $related = Bulletin::with(['kategoriBulletin', 'author', 'lembaga'])
            ->published()
            ->where('id', '!=', $bulletin->id)
            ->where('kategori_bulletin_id', $bulletin->kategori_bulletin_id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        // Tambal ke 3 jika kurang
        if ($related->count() < 3) {
            $existingIds = $related->pluck('id')->push($bulletin->id);

            // ← PERBAIKAN: tambah eager load 'lembaga'
            $tambahan = Bulletin::with(['kategoriBulletin', 'author', 'lembaga'])
                ->published()
                ->whereNotIn('id', $existingIds)
                ->latest('published_at')
                ->limit(3 - $related->count())
                ->get();

            $related = $related->merge($tambahan);
        }

        return view('pages.bulletin-show', compact('bulletin', 'related'));
    }

    public function laporan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan');
        $q     = $request->get('q');

        $query = \App\Models\LaporanKeuanganLembaga::with('lembaga')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc');

        // Filter tahun
        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        // Filter bulan
        if ($bulan) {
            $query->where('bulan', $bulan);
        }

        // Search nama lembaga
        if ($q) {
            $query->whereHas('lembaga', function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('kode_lembaga', 'like', "%{$q}%")
                    ->orWhere('kota_nama', 'like', "%{$q}%");
            });
        }

        $laporan = $query->paginate(12);

        // Available tahun untuk filter
        $availableTahun = \App\Models\LaporanKeuanganLembaga::where('status', 'published')
            ->selectRaw('DISTINCT tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (!in_array(date('Y'), $availableTahun)) {
            array_unshift($availableTahun, (int) date('Y'));
        }

        // Summary stats untuk hero
        $totalLaporan  = \App\Models\LaporanKeuanganLembaga::where('status', 'published')->count();
        $totalLembaga  = \App\Models\Lembaga::where('is_active', true)->count();

        $totalDana = \App\Models\LaporanKeuanganLembaga::where('status', 'published')
            ->sum('total_penyaluran');
        $totalDanaDisalurkan = 'Rp ' . $this->formatAngka($totalDana);

        return view('pages.laporan', compact(
            'laporan',
            'availableTahun',
            'totalLaporan',
            'totalLembaga',
            'totalDanaDisalurkan',
        ));
    }


    private function formatAngka(float $angka): string
    {
        if ($angka >= 1_000_000_000) {
            return number_format($angka / 1_000_000_000, 1, ',', '.') . ' M';
        }
        if ($angka >= 1_000_000) {
            return number_format($angka / 1_000_000, 1, ',', '.') . ' Jt';
        }
        return number_format($angka, 0, ',', '.');
    }

    public function laporanDetail(Request $request, $id)
{
    // Ambil lembaga beserta laporan keuangannya
    $lembaga = Lembaga::findOrFail($id);
    
    // Buat query untuk laporan keuangan
    $query = \App\Models\LaporanKeuanganLembaga::where('lembaga_id', $lembaga->id)
        ->where('status', 'published')
        ->orderBy('tahun', 'desc')
        ->orderBy('bulan', 'desc');
    
    // Ambil semua tahun yang tersedia untuk filtering
    $availableTahun = \App\Models\LaporanKeuanganLembaga::where('lembaga_id', $lembaga->id)
        ->where('status', 'published')
        ->select('tahun')
        ->distinct()
        ->orderBy('tahun', 'desc')
        ->pluck('tahun')
        ->toArray();
    
    // Filter berdasarkan tahun jika ada
    $tahun = $request->get('tahun');
    if ($tahun && in_array($tahun, $availableTahun)) {
        $query->where('tahun', $tahun);
    }
    
    // Ambil laporan terbaru untuk summary
    $latestLaporan = $query->clone()->first();
    
    // Paginate hasilnya
    $laporan = $query->paginate(10);
    
    return view('pages.laporan-detail', compact(
        'lembaga',
        'laporan',
        'latestLaporan',
        'availableTahun'
    ));
}
}