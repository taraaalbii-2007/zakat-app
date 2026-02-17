<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('konfigurasi_aplikasi', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // HANYA TAMPILAN & INFO APLIKASI
            $table->string('nama_aplikasi')->default('Sistem Zakat Digital');
            $table->string('tagline')->nullable();
            $table->text('deskripsi_aplikasi')->nullable();
            $table->string('logo_aplikasi')->nullable();
            $table->string('favicon')->nullable();
            $table->string('email_support')->nullable();
            $table->string('telepon_support', 20)->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('whatsapp_support')->nullable();
            
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_aplikasi');
    }
};