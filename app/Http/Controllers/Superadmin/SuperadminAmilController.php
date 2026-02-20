<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Masjid;

class SuperadminAmilController extends Controller
{
    public function index()
    {
        $masjids = Masjid::with(['amils' => function ($q) {
            $q->orderBy('nama_lengkap');
        }])
        ->withCount('amils')
        ->orderBy('nama')
        ->get();

        $totalAmil = $masjids->sum(fn($m) => $m->amils->count());

        return view('superadmin.amil.index', compact('masjids', 'totalAmil'));
    }
}