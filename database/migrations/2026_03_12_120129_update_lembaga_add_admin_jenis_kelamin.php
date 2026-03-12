<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lembaga', function (Blueprint $table) {
            // Tambah setelah admin_nama
            $table->enum('admin_jenis_kelamin', ['laki-laki', 'perempuan'])
                ->nullable()
                ->after('admin_nama')
                ->comment('Jenis kelamin admin lembaga');
        });
    }

    public function down(): void
    {
        Schema::table('lembaga', function (Blueprint $table) {
            $table->dropColumn('admin_jenis_kelamin');
        });
    }
};