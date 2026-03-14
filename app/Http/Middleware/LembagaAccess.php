<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lembaga;
use Symfony\Component\HttpFoundation\Response;

class LembagaAccess
{
    /**
     * Middleware untuk memastikan user memiliki akses ke lembaga
     * Digunakan untuk route yang membutuhkan data lembaga
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

        if ($user->peran === 'muzakki') {
            $muzakki = \App\Models\Muzakki::where('pengguna_id', $user->id)->first();
            $lembaga = $muzakki?->lembaga;

            if ($lembaga && !$lembaga->is_active) {
                return $this->logoutNonaktif($request,
                    'Lembaga Anda sedang dinonaktifkan. Silakan hubungi superadmin.'
                );
            }

            return $next($request);
        }

        $lembaga = null;

        if ($user->peran === 'admin_lembaga') {
            $lembaga = Lembaga::find($user->lembaga_id);

            if (!$lembaga) {
                return redirect()->route('admin.konfigurasi.index')
                    ->with('warning', 'Silakan lengkapi profil lembaga terlebih dahulu.');
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

            $lembaga = $amil->lembaga;
        }

        if (!$lembaga) {
            return redirect()->route('dashboard')
                ->with('error', 'Tidak dapat menentukan lembaga Anda.');
        }

        // Cek apakah lembaga masih aktif — auto logout jika tidak
        if (!$lembaga->is_active) {
            return $this->logoutNonaktif($request,
                'Lembaga Anda sedang dinonaktifkan. Silakan hubungi superadmin.'
            );
        }

        $request->attributes->set('lembaga', $lembaga);

        return $next($request);
    }

    /**
     * Logout user dan redirect ke login dengan pesan error
     */
    private function logoutNonaktif(Request $request, string $message): Response
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('error', $message);
    }

    /**
     * Redirect berdasarkan role ketika tidak punya lembaga
     */
    private function redirectNoLembaga($user)
    {
        switch ($user->peran) {
            case 'admin_lembaga':
                return redirect()->route('admin.konfigurasi.index')
                    ->with('warning', 'Silakan lengkapi profil lembaga terlebih dahulu.');

            case 'amil':
                return redirect()->route('dashboard')
                    ->with('error', 'Anda belum ditugaskan di lembaga manapun. Hubungi admin lembaga.');

            default:
                return redirect()->route('login');
        }
    }
}