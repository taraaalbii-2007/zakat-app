<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HargaEmasPerak;
use App\Models\JenisZakat;

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

        return view('layouts.guest', compact('hargaTerbaru', 'jenisZakat'));
    }
}