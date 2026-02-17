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
        // ===============================
        // 5. TABEL PENGGUNA
        // ===============================Z
        
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('masjid_id')->nullable()->constrained('masjid')->onDelete('set null');
            $table->enum('peran', ['superadmin', 'admin_masjid', 'amil'])->default('admin_masjid');
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('google_id')->nullable()->unique();
            $table->text('google_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('verification_token')->nullable();
            $table->timestamp('verification_token_expires_at')->nullable();
            $table->string('password_reset_token')->nullable();
            $table->timestamp('password_reset_token_expires_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('uuid');
            $table->index('peran');
            $table->index('email_verified_at');
            $table->index('is_active');
            $table->index('created_at');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};