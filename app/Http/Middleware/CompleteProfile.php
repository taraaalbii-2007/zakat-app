<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lembaga;
use Symfony\Component\HttpFoundation\Response;

class CompleteProfile
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Hanya untuk admin lembaga
        if ($user->peran === 'admin_lembaga') {
            // Cek apakah user memiliki lembaga_id
            if (!$user->lembaga_id) {
                return redirect()->route('admin-lembaga.konfigurasi-integrasi.index')
                    ->with('warning', 'Silakan lengkapi data lembaga terlebih dahulu.');
            }
            
            $lembaga = $user->lembaga;
            
            // Cek apakah data minimal sudah diisi
            if (!$lembaga) {
                return redirect()->route('admin-lembaga.konfigurasi-integrasi.index')
                    ->with('warning', 'Data lembaga tidak ditemukan.');
            }
            
            // Opsional: cek kelengkapan data, tapi jangan terlalu ketat
            if (empty($lembaga->nama) || empty($lembaga->alamat)) {
                return redirect()->route('admin-lembaga.konfigurasi-integrasi.index')
                    ->with('warning', 'Silakan lengkapi profil lembaga terlebih dahulu.');
            }
        }

        return $next($request);
    }
}