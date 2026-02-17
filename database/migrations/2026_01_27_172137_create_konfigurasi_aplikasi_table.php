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
            
            // INFO APLIKASI
            $table->string('nama_aplikasi')->default('Sistem Zakat Digital');
            $table->string('tagline')->nullable();
            $table->text('deskripsi_aplikasi')->nullable();
            $table->string('versi')->default('1.0.0');
            $table->string('logo_aplikasi')->nullable();
            $table->string('favicon')->nullable();
            
            // KONTAK & SUPPORT
            $table->string('email_admin')->nullable();
            $table->string('telepon_admin', 20)->nullable();
            $table->text('alamat_kantor')->nullable();
            
            // SOCIAL MEDIA
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('whatsapp_support')->nullable();
            
            // HANYA 1 ROW SAJA (untuk superadmin)
            $table->boolean('is_default')->default(true);
            $table->timestamps();

            $table->index('uuid');
            $table->index('is_default');
        });

        Schema::create('recaptcha_configs', function (Blueprint $table) {
            $table->id();
            $table->string('RECAPTCHA_SITE_KEY')->nullable();
            $table->string('RECAPTCHA_SECRET_KEY')->nullable();
            $table->timestamps();
        });

        Schema::create('google_configs', function (Blueprint $table) {
            $table->id();
            $table->string('GOOGLE_CLIENT_ID')->nullable();
            $table->text('GOOGLE_CLIENT_SECRET')->nullable();
            $table->string('GOOGLE_REDIRECT_URI')->nullable();
            $table->timestamps();
        });

        Schema::create('mail_configs', function (Blueprint $table) {
            $table->id();
            $table->string('MAIL_MAILER')->default('smtp');
            $table->string('MAIL_HOST')->nullable();
            $table->string('MAIL_PORT')->default('587');
            $table->string('MAIL_USERNAME')->nullable();
            $table->string('MAIL_PASSWORD')->nullable();
            $table->string('MAIL_ENCRYPTION')->nullable();
            $table->string('MAIL_FROM_ADDRESS')->nullable();
            $table->string('MAIL_FROM_NAME')->nullable();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_aplikasi');
        Schema::dropIfExists('mail_configs');
        Schema::dropIfExists('google_configs');
        Schema::dropIfExists('recaptcha_configs');
    }
};