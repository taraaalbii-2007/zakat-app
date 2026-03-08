<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            // 1. Drop foreign key dulu
            $table->dropForeign(['masjid_id']);

            // 2. Rename kolom
            $table->renameColumn('masjid_id', 'lembaga_id');
        });

        Schema::table('pengguna', function (Blueprint $table) {
            // 3. Buat ulang foreign key ke tabel lembaga (sudah di-rename)
            $table->foreign('lembaga_id')
                  ->references('id')
                  ->on('lembaga')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->dropForeign(['lembaga_id']);
            $table->renameColumn('lembaga_id', 'masjid_id');
        });

        Schema::table('pengguna', function (Blueprint $table) {
            $table->foreign('masjid_id')
                  ->references('id')
                  ->on('masjid')
                  ->onDelete('set null');
        });
    }
};