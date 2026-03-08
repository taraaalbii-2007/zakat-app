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
        Schema::table('konfigurasi_qris', function (Blueprint $table) {
            // Rename kolom masjid_id menjadi lembaga_id
            $table->renameColumn('masjid_id', 'lembaga_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konfigurasi_qris', function (Blueprint $table) {
            // Kembalikan ke nama awal
            $table->renameColumn('lembaga_id', 'masjid_id');
        });
    }
};