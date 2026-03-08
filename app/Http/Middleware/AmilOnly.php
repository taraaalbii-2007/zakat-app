<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lembaga;
use Symfony\Component\HttpFoundation\Response;

class AmilOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Cek role
        if ($user->peran !== 'amil') {
            abort(403, 'Hanya amil yang dapat mengakses halaman ini.');
        }

        // Cek apakah amil sudah ditugaskan di lembaga
        // Menggunakan lembaga_id yang ada di tabel pengguna
        if ($user->lembaga_id) {
            $lembaga = Lembaga::find($user->lembaga_id);
        } else {
            $lembaga = null;
        }
        
        if (!$lembaga) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda belum ditugaskan di lembaga manapun. Hubungi admin lembaga.');
        }

        // Simpan lembaga ke request
        $request->attributes->set('lembaga', $lembaga);

        return $next($request);
    }
}