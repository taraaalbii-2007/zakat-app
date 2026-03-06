<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimoni', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('muzakki_id')->constrained('muzakki')->onDelete('cascade');
            $table->foreignId('transaksi_penerimaan_id')->nullable()->constrained('transaksi_penerimaan')->onDelete('set null');
            $table->string('nama_pengirim');
            $table->string('pekerjaan')->nullable();
            $table->text('isi_testimoni');
            $table->tinyInteger('rating')->unsigned()->default(5); // 1-5
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimoni');
    }
};