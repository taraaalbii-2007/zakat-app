<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Masjid;

class SuperadminTransaksiPenerimaanController extends Controller
{
    public function index()
    {
        $masjids = Masjid::with(['transaksiPenerimaan' => function ($q) {
            $q->with('jenisZakat')->orderBy('created_at', 'desc');
        }])
        ->orderBy('nama')
        ->get();

        $totalTransaksi = $masjids->sum(fn($m) => $m->transaksiPenerimaan->count());
        $totalVerified  = $masjids->sum(fn($m) => $m->transaksiPenerimaan->where('status', 'verified')->count());
        $totalPending   = $masjids->sum(fn($m) => $m->transaksiPenerimaan->where('status', 'pending')->count());
        $totalNominal   = $masjids->sum(fn($m) => $m->transaksiPenerimaan->sum('jumlah'));

        return view('superadmin.transaksi-penerimaan.index',
            compact('masjids', 'totalTransaksi', 'totalVerified', 'totalPending', 'totalNominal'));
    }
}