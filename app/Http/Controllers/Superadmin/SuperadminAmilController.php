<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;

class SuperadminAmilController extends Controller
{
    public function index()
    {
        $lembagas = Lembaga::with(['amils' => function ($q) {
            $q->orderBy('nama_lengkap');
        }])
        ->withCount('amils')
        ->orderBy('nama')
        ->get();

        $totalAmil = $lembagas->sum(fn($m) => $m->amils->count());

        $breadcrumbs = [
            'Kelola Amil' => null,
        ];


        return view('superadmin.amil.index', compact('lembagas', 'totalAmil','breadcrumbs'));
    }
}