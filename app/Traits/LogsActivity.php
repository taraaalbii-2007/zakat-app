<?php

namespace App\Traits;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Log;


trait LogsActivity
{
   // Di app/Traits/LogsActivity.php
protected function logAuth(string $aktivitas, string $deskripsi, ?array $dataBaru = null, ?int $penggunaId = null): void
{
    // Cek apakah pengguna dengan ID tersebut benar-benar ada
    if ($penggunaId && !\App\Models\Pengguna::where('id', $penggunaId)->exists()) {
        Log::warning('Mencoba log dengan pengguna_id yang tidak ada: ' . $penggunaId);
        return; // Jangan lanjutkan logging
    }
    
    LogAktivitas::catat($aktivitas, 'auth', $deskripsi, null, $dataBaru, $penggunaId);
}

protected function logRegistrasi(string $deskripsi, ?array $dataBaru = null, ?int $penggunaId = null): void
{
    // Cek apakah pengguna dengan ID tersebut benar-benar ada
    if ($penggunaId && !\App\Models\Pengguna::where('id', $penggunaId)->exists()) {
        Log::warning('Mencoba log registrasi dengan pengguna_id yang tidak ada: ' . $penggunaId);
        return; // Jangan lanjutkan logging
    }
    
    LogAktivitas::catat('create', 'registrasi', $deskripsi, null, $dataBaru, $penggunaId);
}

    /**
     * Catat aktivitas umum (fleksibel)
     */
    protected function logAktivitas(
        string $aktivitas,
        string $modul,
        string $deskripsi,
        ?array $dataLama = null,
        ?array $dataBaru = null,
        ?int $penggunaId = null
    ): void {
        LogAktivitas::catat($aktivitas, $modul, $deskripsi, $dataLama, $dataBaru, $penggunaId);
    }
}