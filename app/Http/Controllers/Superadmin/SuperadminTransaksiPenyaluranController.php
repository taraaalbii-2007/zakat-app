<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;

class SuperadminTransaksiPenyaluranController extends Controller
{
    public function index()
    {
        $lembagas = Lembaga::with(['transaksiPenyaluran' => function ($q) {
            $q->with('mustahik')->orderBy('created_at', 'desc');
        }])
        ->orderBy('nama')
        ->get();

        $totalTransaksi  = $lembagas->sum(fn($m) => $m->transaksiPenyaluran->count());
        $totalDraft      = $lembagas->sum(fn($m) => $m->transaksiPenyaluran->where('status', 'draft')->count());
        $totalDisalurkan = $lembagas->sum(fn($m) => $m->transaksiPenyaluran->where('status', 'disalurkan')->count());
        $totalNominal    = $lembagas->sum(fn($m) => $m->transaksiPenyaluran->sum('jumlah'));

        $breadcrumbs = [
            'Kelola Penyaluran' => null,
        ];

        return view('superadmin.transaksi-penyaluran.index',
            compact('lembagas', 'totalTransaksi', 'totalDraft', 'totalDisalurkan', 'totalNominal', 'breadcrumbs'));
    }
}