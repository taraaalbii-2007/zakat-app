<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_penerimaan', function (Blueprint $table) {

            // Relasi ke tabel muzakki (nullable, untuk backward compat data lama)
            $table->foreignId('muzakki_id')
                ->nullable()
                ->after('masjid_id')
                ->constrained('muzakki')
                ->onDelete('set null')
                ->comment('Relasi ke tabel muzakki jika muzakki punya akun. NULL = input manual oleh amil');

            // Flag apakah transaksi diinput sendiri oleh muzakki via dashboard
            $table->boolean('diinput_muzakki')
                ->default(false)
                ->after('muzakki_id')
                ->comment('TRUE jika muzakki input sendiri via dashboard, FALSE jika diinput amil');
        });

        // Tambah nilai 'daring' ke enum metode_penerimaan
        DB::statement("ALTER TABLE transaksi_penerimaan MODIFY COLUMN metode_penerimaan ENUM('datang_langsung', 'dijemput', 'daring') NOT NULL DEFAULT 'datang_langsung'");
    }

    public function down(): void
    {
        Schema::table('transaksi_penerimaan', function (Blueprint $table) {
            $table->dropForeign(['muzakki_id']);
            $table->dropColumn(['muzakki_id', 'diinput_muzakki']);
        });

        DB::statement("ALTER TABLE transaksi_penerimaan MODIFY COLUMN metode_penerimaan ENUM('datang_langsung', 'dijemput') NOT NULL DEFAULT 'datang_langsung'");
    }
};