<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HargaEmasPerak;
use App\Models\JenisZakat;
use App\Models\Mustahik;
use App\Models\ProgramZakat;
use App\Models\TransaksiPenyaluran;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil harga emas perak terbaru yang aktif (ditampilkan di hero card)
        $hargaTerbaru = HargaEmasPerak::where('is_active', true)
            ->orderBy('tanggal', 'desc')
            ->first();

        // Ambil daftar jenis zakat aktif
        $jenisZakat = JenisZakat::orderBy('nama')->get();

        // === STATISTIK DINAMIS ===

        // Total Muzaki unik (berdasarkan muzakki_nama + masjid_id di transaksi_penerimaan)
        $totalMuzaki = DB::table('transaksi_penerimaan')
            ->whereNotNull('muzakki_nama')
            ->selectRaw('COUNT(DISTINCT CONCAT(muzakki_nama, "-", masjid_id)) as cnt')
            ->value('cnt') ?? 0;

        // Total Mustahik terdaftar
        $totalMustahik = Mustahik::count();

        // Total dana tersalurkan (status disalurkan atau disetujui)
        $totalDana = TransaksiPenyaluran::whereIn('status', ['disalurkan', 'disetujui'])
            ->sum(DB::raw('CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE COALESCE(jumlah, 0) END'));

        // Total program zakat
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
        $hargaTerbaru    = HargaEmasPerak::where('is_active', true)->orderBy('tanggal', 'desc')->first();
        $hargaEmasPerGram = $hargaTerbaru ? (int) $hargaTerbaru->harga_emas_per_gram : 1900000;
        $nisabMaal        = $hargaEmasPerGram * 85;
        $nisabBulanan     = (int) round($nisabMaal / 12);

        return view('pages.hitung-zakat', compact('hargaTerbaru', 'hargaEmasPerGram', 'nisabMaal', 'nisabBulanan'));
    }

    public function panduanZakat()
    {
        return view('pages.panduan-zakat');
    }
}