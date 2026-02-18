<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Masjid;
use Symfony\Component\HttpFoundation\Response;

class MasjidAccess
{
    /**
     * Middleware untuk memastikan user memiliki akses ke masjid
     * Digunakan untuk route yang membutuhkan data masjid
     */
public function handle(Request $request, Closure $next): Response
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->peran === 'superadmin') {
        return $next($request);
    }

    $masjid = null; // ← inisialisasi dulu

    if ($user->peran === 'admin_masjid') {
        $masjid = Masjid::find($user->masjid_id);

        if (!$masjid) {
            return redirect()->route('admin.konfigurasi.index')
                ->with('warning', 'Silakan lengkapi profil masjid terlebih dahulu.');
        }
    }

    if ($user->peran === 'amil') {
        $amil = \App\Models\Amil::where('pengguna_id', $user->id)
            ->where('status', 'aktif')
            ->first();

        if (!$amil) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda belum ditugaskan sebagai amil aktif.');
        }

        $masjid = $amil->masjid;
    }

    if (!$masjid) {
        return redirect()->route('dashboard')
            ->with('error', 'Tidak dapat menentukan masjid Anda.');
    }

    $request->attributes->set('masjid', $masjid);

    return $next($request);
}

    /**
     * Redirect berdasarkan role ketika tidak punya masjid
     */
    private function redirectNoMasjid($user)
    {
        switch ($user->peran) {
            case 'admin_masjid':
                return redirect()->route('admin.konfigurasi.index')
                    ->with('warning', 'Silakan lengkapi profil masjid terlebih dahulu.');
            
            case 'amil':
                return redirect()->route('dashboard')
                    ->with('error', 'Anda belum ditugaskan di masjid manapun. Hubungi admin masjid.');
            
            default:
                return redirect()->route('login');
        }
    }
}