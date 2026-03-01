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
        // Tabel Master Kategori Bulletin
        Schema::create('kategori_bulletin', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama_kategori');
            $table->timestamps();
        });

        // Tabel Bulletins
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Author
            $table->foreignId('created_by')->nullable()->constrained('pengguna')->onDelete('set null');

            // Kategori
            $table->foreignId('kategori_bulletin_id')->constrained('kategori_bulletin')->onDelete('restrict');

            // Konten
            $table->string('judul');
            $table->string('slug')->unique();
            $table->longText('konten');
            $table->string('lokasi')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('image_caption')->nullable();

            // Auto publish - langsung tampil
            $table->timestamp('published_at')->useCurrent();

            // Statistik
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Index untuk performa
            $table->index(['published_at']);
            $table->index(['kategori_bulletin_id', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletins');
        Schema::dropIfExists('kategori_bulletin');
    }
};