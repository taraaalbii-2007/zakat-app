<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tabel = [
            'transaksi_penerimaan',
            'transaksi_penyaluran',
            'mustahik',
            'amil',
            'kas_harian_amil',
            'program_zakat',
            'setor_kas',
        ];

        foreach ($tabel as $nama) {
            Schema::table($nama, function (Blueprint $table) {
                $table->renameColumn('masjid_id', 'lembaga_id');
            });
        }
    }

    public function down(): void
    {
        $tabel = [
            'transaksi_penerimaan',
            'transaksi_penyaluran',
            'mustahik',
            'amil',
            'kas_harian_amil',
            'program_zakat',
            'setor_kas',
        ];

        foreach ($tabel as $nama) {
            Schema::table($nama, function (Blueprint $table) {
                $table->renameColumn('lembaga_id', 'masjid_id');
            });
        }
    }
};