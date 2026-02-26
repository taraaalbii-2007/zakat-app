<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konfigurasi_qris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjid')->onDelete('cascade');
            $table->string('qris_image_path')->nullable()->comment('Path gambar QRIS');
            $table->boolean('is_active')->default(false)->comment('Status aktif QRIS');
            $table->timestamps();
            
            // Indexes
            $table->index('masjid_id');
            $table->index('is_active');
            $table->unique(['masjid_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_qris');
    }
};