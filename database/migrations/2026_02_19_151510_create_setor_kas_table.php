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
        Schema::create('setor_kas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('no_setor')->unique();
            $table->date('tanggal_setor');
            $table->date('periode_dari');
            $table->date('periode_sampai');
            $table->foreignId('amil_id')->constrained('amil')->onDelete('restrict');
            $table->foreignId('masjid_id')->constrained('masjid')->onDelete('restrict');
            $table->foreignId('diterima_oleh')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->decimal('jumlah_disetor', 20, 2);
            $table->decimal('jumlah_dari_datang_langsung', 20, 2)->default(0);
            $table->decimal('jumlah_dari_dijemput', 20, 2)->default(0);
            $table->string('bukti_foto')->nullable();
            $table->string('tanda_tangan_amil')->nullable();
            $table->string('tanda_tangan_penerima')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamp('diterima_at')->nullable();
            $table->decimal('jumlah_dihitung_fisik', 20, 2)->nullable();
            $table->timestamp('ditolak_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['amil_id', 'tanggal_setor']);
            $table->index('masjid_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setor_kas');
    }
};