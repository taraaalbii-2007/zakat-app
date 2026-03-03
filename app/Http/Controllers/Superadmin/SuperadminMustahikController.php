<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Masjid;

class SuperadminMustahikController extends Controller
{
    public function index()
    {
        $masjids = Masjid::with(['mustahiks' => function ($q) {
            $q->with('kategoriMustahik')->orderBy('nama_lengkap');
        }])
        ->orderBy('nama')
        ->get();

        $totalMustahik = $masjids->sum(fn($m) => $m->mustahiks->count());
        $breadcrumbs = [
            'Kelola Mustahik' => null,
        ];

        return view('superadmin.mustahik.index', compact('masjids', 'totalMustahik', 'breadcrumbs'));
    }
}