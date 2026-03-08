<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Rename tabel
        Schema::rename('masjid', 'lembaga');

        // 2. Rename kolom kode_masjid -> kode_lembaga
        Schema::table('lembaga', function (Blueprint $table) {
            $table->renameColumn('kode_masjid', 'kode_lembaga');
        });
    }

    public function down(): void
    {
        Schema::table('lembaga', function (Blueprint $table) {
            $table->renameColumn('kode_lembaga', 'kode_masjid');
        });

        Schema::rename('lembaga', 'masjid');
    }
};