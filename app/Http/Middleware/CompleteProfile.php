<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Masjid;
use Symfony\Component\HttpFoundation\Response;

class CompleteProfile
{
    /**
     * Middleware untuk memastikan admin masjid sudah lengkapi profil
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Hanya untuk admin masjid
        if ($user->peran === 'admin_masjid') {
            // Gunakan masjid_id dari user, bukan query ke tabel masjid
            $masjid = $user->masjid; // Menggunakan relasi eloquent
            
            // ATAU jika relasi belum didefinisikan di model:
            // $masjid = Masjid::find($user->masjid_id);
            
            // Cek apakah data minimal sudah diisi
            if (!$masjid || 
                !$masjid->nama || 
                !$masjid->alamat || 
                !$masjid->provinsi_kode || 
                !$masjid->kota_kode) {
                
                return redirect()->route('admin.konfigurasi.index')
                    ->with('warning', 'Silakan lengkapi profil masjid terlebih dahulu.');
            }
        }

        return $next($request);
    }
}