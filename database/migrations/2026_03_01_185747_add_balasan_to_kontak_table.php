<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom balasan & dibalas_at ke tabel kontak.
     * Jalankan ini SETELAH migration kontak utama sudah ada.
     */
    public function up(): void
    {
        Schema::table('kontak', function (Blueprint $table) {
            $table->longText('balasan')->nullable()->after('dibaca_at');
            $table->dateTime('dibalas_at')->nullable()->after('balasan');
        });
    }

    public function down(): void
    {
        Schema::table('kontak', function (Blueprint $table) {
            $table->dropColumn(['balasan', 'dibalas_at']);
        });
    }
};