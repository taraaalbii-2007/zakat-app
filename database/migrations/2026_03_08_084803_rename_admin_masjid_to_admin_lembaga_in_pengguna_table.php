<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah admin_lembaga ke enum DULU (sambil pertahankan admin_masjid)
        DB::statement("ALTER TABLE pengguna MODIFY COLUMN peran ENUM('superadmin', 'admin_masjid', 'admin_lembaga', 'amil', 'muzakki') NOT NULL DEFAULT 'admin_masjid'");

        // 2. Baru update datanya
        DB::statement("UPDATE pengguna SET peran = 'admin_lembaga' WHERE peran = 'admin_masjid'");

        // 3. Hapus admin_masjid dari enum
        DB::statement("ALTER TABLE pengguna MODIFY COLUMN peran ENUM('superadmin', 'admin_lembaga', 'amil', 'muzakki') NOT NULL DEFAULT 'muzakki'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pengguna MODIFY COLUMN peran ENUM('superadmin', 'admin_masjid', 'admin_lembaga', 'amil', 'muzakki') NOT NULL DEFAULT 'admin_lembaga'");

        DB::statement("UPDATE pengguna SET peran = 'admin_masjid' WHERE peran = 'admin_lembaga'");

        DB::statement("ALTER TABLE pengguna MODIFY COLUMN peran ENUM('superadmin', 'admin_masjid', 'amil', 'muzakki') NOT NULL DEFAULT 'muzakki'");
    }
};