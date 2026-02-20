<?php

namespace App\Traits;

use App\Models\LogAktivitas;

trait LogsAuthActivity
{
    protected function logLogin($user, $request)
    {
        LogAktivitas::catat(
            aktivitas: 'login',
            modul: 'auth',
            deskripsi: "Login berhasil: {$user->email}",
            dataBaru: [
                'email' => $user->email,
                'role' => $user->peran,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ],
            penggunaId: $user->id
        );
    }

    protected function logFailedLogin($email, $request)
    {
        LogAktivitas::catat(
            aktivitas: 'login_failed',
            modul: 'auth',
            deskripsi: "Percobaan login gagal untuk email: {$email}",
            dataBaru: [
                'email' => $email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ],
            penggunaId: null
        );
    }

    protected function logLogout($user, $request)
    {
        LogAktivitas::catat(
            aktivitas: 'logout',
            modul: 'auth',
            deskripsi: "Logout: {$user->email}",
            dataBaru: [
                'email' => $user->email,
                'ip' => $request->ip()
            ],
            penggunaId: $user->id
        );
    }

    protected function logRegister($user, $request)
    {
        LogAktivitas::catat(
            aktivitas: 'register',
            modul: 'auth',
            deskripsi: "Registrasi user baru: {$user->email}",
            dataBaru: $user->toArray(),
            penggunaId: $user->id
        );
    }
}