<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('muzakki', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Relasi ke akun pengguna
            $table->foreignId('pengguna_id')
                ->nullable()
                ->constrained('pengguna')
                ->onDelete('set null')
                ->comment('NULL jika muzakki guest (tidak punya akun)');

            // Muzakki bisa terdaftar di satu masjid utama
            $table->foreignId('masjid_id')
                ->nullable()
                ->constrained('masjid')
                ->onDelete('set null')
                ->comment('Masjid yang dipilih muzakki sebagai tempat berzakat');

            // Data pribadi
            $table->string('nama');
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nik', 16)->nullable()->unique()->comment('NIK KTP, opsional');

            // Foto profil
            $table->string('foto')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('pengguna_id');
            $table->index('masjid_id');
            $table->index('nik');
            $table->index('nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('muzakki');
    }
};