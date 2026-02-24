<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Muzakki
{
    /**
     * Middleware untuk memastikan user memiliki peran muzakki
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Jika belum login
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Jika bukan muzakki
        if ($user->peran !== 'muzakki') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk muzakki.');
        }

        return $next($request);
    }
}