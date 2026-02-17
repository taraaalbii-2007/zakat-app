<?php

namespace App\Traits;

use App\Models\LogAktivitas;

trait LogsActivity
{
    /**
     * Log aktivitas create
     */
    protected function logCreate(string $modul, string $deskripsi, ?array $dataBaru = null)
    {
        return LogAktivitas::catat(
            aktivitas: 'create',
            modul: $modul,
            deskripsi: $deskripsi,
            dataBaru: $dataBaru
        );
    }

    /**
     * Log aktivitas update
     */
    protected function logUpdate(string $modul, string $deskripsi, ?array $dataLama = null, ?array $dataBaru = null)
    {
        return LogAktivitas::catat(
            aktivitas: 'update',
            modul: $modul,
            deskripsi: $deskripsi,
            dataLama: $dataLama,
            dataBaru: $dataBaru
        );
    }

    /**
     * Log aktivitas delete
     */
    protected function logDelete(string $modul, string $deskripsi, ?array $dataLama = null)
    {
        return LogAktivitas::catat(
            aktivitas: 'delete',
            modul: $modul,
            deskripsi: $deskripsi,
            dataLama: $dataLama
        );
    }

    /**
     * Log aktivitas login
     */
    protected function logLogin(string $deskripsi = 'Pengguna berhasil login')
    {
        return LogAktivitas::catat(
            aktivitas: 'login',
            modul: 'auth',
            deskripsi: $deskripsi
        );
    }

    /**
     * Log aktivitas logout
     */
    protected function logLogout(string $deskripsi = 'Pengguna logout dari sistem')
    {
        return LogAktivitas::catat(
            aktivitas: 'logout',
            modul: 'auth',
            deskripsi: $deskripsi
        );
    }

    /**
     * Log aktivitas approve
     */
    protected function logApprove(string $modul, string $deskripsi, ?array $dataLama = null, ?array $dataBaru = null)
    {
        return LogAktivitas::catat(
            aktivitas: 'approve',
            modul: $modul,
            deskripsi: $deskripsi,
            dataLama: $dataLama,
            dataBaru: $dataBaru
        );
    }

    /**
     * Log aktivitas view (opsional, untuk aktivitas penting saja)
     */
    protected function logView(string $modul, string $deskripsi)
    {
        return LogAktivitas::catat(
            aktivitas: 'view',
            modul: $modul,
            deskripsi: $deskripsi
        );
    }

    /**
     * Log aktivitas custom
     */
    protected function logActivity(
        string $aktivitas,
        string $modul,
        string $deskripsi,
        ?array $dataLama = null,
        ?array $dataBaru = null,
        ?int $penggunaId = null
    ) {
        return LogAktivitas::catat(
            aktivitas: $aktivitas,
            modul: $modul,
            deskripsi: $deskripsi,
            dataLama: $dataLama,
            dataBaru: $dataBaru,
            penggunaId: $penggunaId
        );
    }
}