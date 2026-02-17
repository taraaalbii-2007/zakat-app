<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('amil', function (Blueprint $table) {
            // Kolom tanda tangan — simpan path file gambar
            $table->string('tanda_tangan')->nullable()->after('foto')
                ->comment('Path file gambar tanda tangan amil di storage/public/amil/ttd');
        });
    }

    public function down(): void
    {
        Schema::table('amil', function (Blueprint $table) {
            $table->dropColumn('tanda_tangan');
        });
    }
};