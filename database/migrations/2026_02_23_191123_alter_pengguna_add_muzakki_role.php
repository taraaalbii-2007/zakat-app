<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pengguna MODIFY COLUMN peran ENUM('superadmin', 'admin_masjid', 'amil', 'muzakki') NOT NULL DEFAULT 'muzakki'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pengguna MODIFY COLUMN peran ENUM('superadmin', 'admin_masjid', 'amil') NOT NULL DEFAULT 'admin_masjid'");
    }
};