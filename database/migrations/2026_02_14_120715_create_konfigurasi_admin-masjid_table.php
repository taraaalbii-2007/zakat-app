<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('konfigurasi_whatsapp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjid')->onDelete('cascade');
            $table->string('api_key')->nullable()->comment('API Key WhatsApp');
            $table->string('nomor_pengirim')->nullable()->comment('Nomor WhatsApp Pengirim');
            $table->string('api_url')->default('https://api.fonnte.com/send')->comment('URL API WhatsApp');
            $table->string('nomor_tujuan_default')->nullable()->comment('Nomor WhatsApp tujuan default untuk notifikasi');
            $table->boolean('is_active')->default(false)->comment('Status aktif WhatsApp');
            $table->timestamps();
            
            // Indexes
            $table->index('masjid_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        // Hapus foreign key constraints dulu (Laravel akan otomatis handle karena onDelete cascade)
        Schema::dropIfExists('konfigurasi_whatsapp');
    }
};