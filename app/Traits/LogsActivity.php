<?php

namespace App\Traits;

use App\Models\LogAktivitas;

trait LogsActivity
{
    /**
     * Catat aktivitas auth (login, logout, dll)
     */
    protected function logAuth(string $aktivitas, string $deskripsi, ?array $dataBaru = null, ?int $penggunaId = null): void
    {
        LogAktivitas::catat($aktivitas, 'auth', $deskripsi, null, $dataBaru, $penggunaId);
    }

    /**
     * Catat aktivitas registrasi
     */
    protected function logRegistrasi(string $deskripsi, ?array $dataBaru = null, ?int $penggunaId = null): void
    {
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