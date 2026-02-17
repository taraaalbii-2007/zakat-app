<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Masjid;
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

        // Cek apakah amil sudah ditugaskan di masjid
        // Menggunakan masjid_id yang ada di tabel pengguna
        if ($user->masjid_id) {
            $masjid = Masjid::find($user->masjid_id);
        } else {
            $masjid = null;
        }
        
        if (!$masjid) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda belum ditugaskan di masjid manapun. Hubungi admin masjid.');
        }

        // Simpan masjid ke request
        $request->attributes->set('masjid', $masjid);

        return $next($request);
    }
}