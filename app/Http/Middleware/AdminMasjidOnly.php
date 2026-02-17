<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Masjid;
use Symfony\Component\HttpFoundation\Response;

class AdminMasjidOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Cek role
        if ($user->peran !== 'admin_masjid') {
            abort(403, 'Hanya admin masjid yang dapat mengakses halaman ini.');
        }

        // ✅ PERBAIKAN: Gunakan relasi yang sudah ada di model Pengguna
        $masjid = $user->masjid; // Ini akan menggunakan relasi masjid() (belongsTo)
        
        if (!$masjid) {
            // Jika belum punya masjid, redirect ke halaman konfigurasi
            return redirect()->route('admin.konfigurasi.index')
                ->with('warning', 'Silakan lengkapi profil masjid terlebih dahulu.');
        }

        // Simpan masjid ke request untuk digunakan di controller
        $request->attributes->set('masjid', $masjid);

        return $next($request);
    }
}