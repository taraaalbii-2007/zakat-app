<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('masjid', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // 1. DATA ADMIN MASJID (PALING ATAS)
            $table->string('admin_nama')->nullable();
            $table->string('admin_telepon')->nullable();
            $table->string('admin_email')->nullable();
            $table->string('admin_foto')->nullable();
            
            // 2. DATA SEJARAH
            $table->text('sejarah')->nullable();
            $table->year('tahun_berdiri')->nullable();
            $table->string('pendiri')->nullable();
            $table->integer('kapasitas_jamaah')->nullable();
            
            // 3. DATA MASJID
            $table->string('nama');
            $table->string('kode_masjid')->unique();
            $table->text('alamat');
            
            // Kode wilayah (char)
            $table->char('provinsi_kode', 2)->nullable();
            $table->char('kota_kode', 4)->nullable();
            $table->char('kecamatan_kode', 10)->nullable();
            $table->char('kelurahan_kode', 13)->nullable();

            // Nama wilayah (denormalized)
            $table->string('provinsi_nama')->nullable();
            $table->string('kota_nama')->nullable();
            $table->string('kecamatan_nama')->nullable();
            $table->string('kelurahan_nama')->nullable();

            // Kontak & lokasi
            $table->string('kode_pos')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('deskripsi')->nullable();
            
            // FOTO MASJID
            $table->json('foto')->nullable();
            
            // STATUS
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign keys ke tabel wilayah
            $table->foreign('provinsi_kode')->references('code')->on('indonesia_provinces')->onDelete('set null');
            $table->foreign('kota_kode')->references('code')->on('indonesia_cities')->onDelete('set null');
            $table->foreign('kecamatan_kode')->references('code')->on('indonesia_districts')->onDelete('set null');
            $table->foreign('kelurahan_kode')->references('code')->on('indonesia_villages')->onDelete('set null');

            // Indexes
            $table->index('uuid');
            $table->index('kode_masjid');
            $table->index('provinsi_kode');
            $table->index('kota_kode');
            $table->index('is_active');
            $table->index('created_at');
            $table->index('tahun_berdiri');
        });
    }

    public function down(): void
    {
        Schema::table('masjid', function (Blueprint $table) {
            $table->dropForeign(['provinsi_kode']);
            $table->dropForeign(['kota_kode']);
            $table->dropForeign(['kecamatan_kode']);
            $table->dropForeign(['kelurahan_kode']);
        });

        Schema::dropIfExists('masjid');
    }
};