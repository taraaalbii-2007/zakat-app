<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename tabel
        Schema::rename('laporan_keuangan_masjid', 'laporan_keuangan_lembaga');
        
        // Rename kolom di dalam tabel
        Schema::table('laporan_keuangan_lembaga', function (Blueprint $table) {
            $table->renameColumn('masjid_id', 'lembaga_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan nama kolom
        Schema::table('laporan_keuangan_lembaga', function (Blueprint $table) {
            $table->renameColumn('lembaga_id', 'masjid_id');
        });
        
        // Kembalikan nama tabel
        Schema::rename('laporan_keuangan_lembaga', 'laporan_keuangan_masjid');
    }
};