<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom nama_jiwa_json ke tabel transaksi_penerimaan
     * untuk menyimpan daftar nama jiwa pada zakat fitrah.
     *
     * Kolom ini diisi array nama (JSON) saat muzakki membayar zakat fitrah
     * dan memasukkan nama-nama anggota keluarga/jiwa yang dizakati.
     *
     * Contoh isi kolom:
     * ["Ahmad Fauzi", "Siti Rahayu", "Budi Santoso"]
     */
    public function up(): void
    {
        Schema::table('transaksi_penerimaan', function (Blueprint $table) {
            $table->json('nama_jiwa_json')
                ->nullable()
                ->after('jumlah_jiwa')
                ->comment('Daftar nama jiwa untuk zakat fitrah (JSON array)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_penerimaan', function (Blueprint $table) {
            $table->dropColumn('nama_jiwa_json');
        });
    }
};