<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;

class SuperadminTransaksiPenerimaanController extends Controller
{
    public function index()
    {
        $lembagas = Lembaga::with(['transaksiPenerimaan' => function ($q) {
            $q->with('jenisZakat')->orderBy('created_at', 'desc');
        }])
        ->orderBy('nama')
        ->get();

        $totalTransaksi = $lembagas->sum(fn($m) => $m->transaksiPenerimaan->count());
        $totalVerified  = $lembagas->sum(fn($m) => $m->transaksiPenerimaan->where('status', 'verified')->count());
        $totalPending   = $lembagas->sum(fn($m) => $m->transaksiPenerimaan->where('status', 'pending')->count());
        $totalNominal   = $lembagas->sum(fn($m) => $m->transaksiPenerimaan->sum('jumlah'));

        $breadcrumbs = [
            'Kelola Transaksi' => null,
        ];

        return view('superadmin.transaksi-penerimaan.index',
            compact('lembagas', 'totalTransaksi', 'totalVerified', 'totalPending', 'totalNominal', 'breadcrumbs'));
    }
}