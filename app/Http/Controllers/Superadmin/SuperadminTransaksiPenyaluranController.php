<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Masjid;

class SuperadminTransaksiPenyaluranController extends Controller
{
    public function index()
    {
        $masjids = Masjid::with(['transaksiPenyaluran' => function ($q) {
            $q->with('mustahik')->orderBy('created_at', 'desc');
        }])
        ->orderBy('nama')
        ->get();

        $totalTransaksi  = $masjids->sum(fn($m) => $m->transaksiPenyaluran->count());
        $totalDraft      = $masjids->sum(fn($m) => $m->transaksiPenyaluran->where('status', 'draft')->count());
        $totalDisalurkan = $masjids->sum(fn($m) => $m->transaksiPenyaluran->where('status', 'disalurkan')->count());
        $totalNominal    = $masjids->sum(fn($m) => $m->transaksiPenyaluran->sum('jumlah'));

        return view('superadmin.transaksi-penyaluran.index',
            compact('masjids', 'totalTransaksi', 'totalDraft', 'totalDisalurkan', 'totalNominal'));
    }
}