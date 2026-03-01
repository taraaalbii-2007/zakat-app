<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HargaEmasPerak;
use App\Models\JenisZakat;
use App\Models\Mustahik;
use App\Models\Bulletin;
use App\Models\KategoriBulletin;
use App\Models\ProgramZakat;
use App\Models\TransaksiPenyaluran;
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
            ->selectRaw('COUNT(DISTINCT CONCAT(muzakki_nama, "-", masjid_id)) as cnt')
            ->value('cnt') ?? 0;

        $totalMustahik = Mustahik::count();

        $totalDana = TransaksiPenyaluran::whereIn('status', ['disalurkan', 'disetujui'])
            ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE COALESCE(jumlah, 0) END'));

        $totalProgram = ProgramZakat::count();

        return view('layouts.guest', compact(
            'hargaTerbaru',
            'jenisZakat',
            'totalMuzaki',
            'totalMustahik',
            'totalDana',
            'totalProgram'
        ));
    }

    public function hitungZakat()
    {
        $hargaTerbaru     = HargaEmasPerak::where('is_active', true)->orderBy('tanggal', 'desc')->first();
        $hargaEmasPerGram = $hargaTerbaru ? (int) $hargaTerbaru->harga_emas_per_gram : 1900000;
        $nisabMaal        = $hargaEmasPerGram * 85;
        $nisabBulanan     = (int) round($nisabMaal / 12);

        return view('pages.hitung-zakat', compact('hargaTerbaru', 'hargaEmasPerGram', 'nisabMaal', 'nisabBulanan'));
    }

    public function panduanZakat()
    {
        return view('pages.panduan-zakat');
    }

    public function artikel(Request $request)
    {
        $query = Bulletin::with(['kategoriBulletin', 'author'])
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

    public function artikelShow(Bulletin $bulletin)
    {
        $bulletin->load(['kategoriBulletin', 'author']);
        $bulletin->incrementViewCount();

        // Prioritas: sekategori dulu
        $related = Bulletin::with(['kategoriBulletin', 'author'])
            ->published()
            ->where('id', '!=', $bulletin->id)
            ->where('kategori_bulletin_id', $bulletin->kategori_bulletin_id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        // Kalau kurang dari 3, tambal dengan artikel lain (beda kategori)
        if ($related->count() < 3) {
            $existingIds = $related->pluck('id')->push($bulletin->id);

            $tambahan = Bulletin::with(['kategoriBulletin', 'author'])
                ->published()
                ->whereNotIn('id', $existingIds)
                ->latest('published_at')
                ->limit(3 - $related->count())
                ->get();

            $related = $related->merge($tambahan);
        }

        return view('pages.bulletin-show', compact('bulletin', 'related'));
    }
}