<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration ini jika tabel muzakki sudah ada (ALTER).
     * Jika membuat ulang dari awal, gunakan file create_muzakki di bawah.
     */
    public function up(): void
    {
        Schema::table('muzakki', function (Blueprint $table) {
            // Tambah jenis_kelamin setelah kolom nama
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])
                ->nullable()
                ->after('nama')
                ->comment('L = Laki-laki, P = Perempuan');
        });
    }

    public function down(): void
    {
        Schema::table('muzakki', function (Blueprint $table) {
            $table->dropColumn('jenis_kelamin');
        });
    }
};