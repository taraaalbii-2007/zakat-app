<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lembaga;
use Symfony\Component\HttpFoundation\Response;

class AdminLembagaOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Superadmin bisa akses semua
        if ($user->peran === 'superadmin') {
            return $next($request);
        }

        // Cek role - PERBAIKI BAGIAN INI
        if ($user->peran !== 'admin_lembaga') {
            // Jangan abort 403, redirect ke dashboard dengan pesan
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Gunakan relasi yang sudah ada di model Pengguna
        $lembaga = $user->lembaga;
        
        if (!$lembaga) {
            return redirect()->route('dashboard')
                ->with('warning', 'Silakan lengkapi profil lembaga terlebih dahulu.');
        }

        // Simpan lembaga ke request untuk digunakan di controller
        $request->attributes->set('lembaga', $lembaga);

        return $next($request);
    }
}