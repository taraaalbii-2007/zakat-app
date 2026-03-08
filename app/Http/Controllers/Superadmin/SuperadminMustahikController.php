<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;

class SuperadminMustahikController extends Controller
{
    public function index()
    {
        $lembagas = Lembaga::with(['mustahiks' => function ($q) {
            $q->with('kategoriMustahik')->orderBy('nama_lengkap');
        }])
        ->orderBy('nama')
        ->get();

        $totalMustahik = $lembagas->sum(fn($m) => $m->mustahiks->count());
        $breadcrumbs = [
            'Kelola Mustahik' => null,
        ];

        return view('superadmin.mustahik.index', compact('lembagas', 'totalMustahik', 'breadcrumbs'));
    }
}