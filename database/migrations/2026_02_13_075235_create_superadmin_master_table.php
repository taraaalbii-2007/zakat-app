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
        // Tabel 1: jenis_zakat (PARENT - Fitrah & Mal)
        Schema::create('jenis_zakat', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama'); // Zakat Fitrah, Zakat Mal
            $table->timestamps();

            $table->index('uuid');
        });

        // Tabel 2: tipe_zakat (CHILD - Detail dari Zakat Mal)
        Schema::create('tipe_zakat', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('jenis_zakat_id')
                ->constrained('jenis_zakat')->onDelete('cascade');
            $table->string('nama');
            
            // Nisab berbasis emas (untuk emas, perak, uang, perdagangan, profesi)
            $table->decimal('nisab_emas_gram', 10, 2)->nullable()->default(85); // 85 gram (standar)
            
            // Nisab berbasis perak (alternatif untuk emas/perak)
            $table->decimal('nisab_perak_gram', 10, 2)->nullable()->default(595); // 595 gram
            
            // Nisab pertanian (hasil bumi)
            $table->decimal('nisab_pertanian_kg', 10, 2)->nullable(); // 653 kg gabah atau 520 kg beras
            
            // Nisab peternakan
            $table->integer('nisab_kambing_min')->nullable(); // 40 ekor
            $table->integer('nisab_sapi_min')->nullable(); // 30 ekor
            $table->integer('nisab_unta_min')->nullable(); // 5 ekor
            
            // Persentase zakat
            $table->decimal('persentase_zakat', 5, 2)->nullable(); // 2.5%, 5%, 10%, 20%
            
            // Persentase alternatif (untuk pertanian: 5% atau 10%)
            $table->decimal('persentase_alternatif', 5, 2)->nullable();
            $table->string('keterangan_persentase')->nullable(); // "10% jika hujan, 5% jika irigasi"
            
            // Ketentuan haul (1 tahun kepemilikan)
            $table->boolean('requires_haul')->default(true);
            
            // Ketentuan khusus per tipe
            $table->text('ketentuan_khusus')->nullable();
            
            $table->timestamps();

            $table->index('uuid');
            $table->index('jenis_zakat_id');
        });

        // Tabel 3: kategori_mustahik
        Schema::create('kategori_mustahik', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama'); // Fakir, Miskin, Amil, Muallaf, Riqab, Gharim, Fisabilillah, Ibnu Sabil
            $table->text('kriteria')->nullable(); // Kriteria penerima
            $table->decimal('persentase_default', 5, 2)->nullable(); // % distribusi default (misal: 12.5% per golongan)
            $table->timestamps();

            $table->index('uuid');
        });

         // Tabel 4: harga_emas_perak
        Schema::create('harga_emas_perak', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->date('tanggal');
            $table->decimal('harga_emas_pergram', 15, 2); // Harga emas per gram
            $table->decimal('harga_perak_pergram', 15, 2); // Harga perak per gram
            $table->string('sumber')->nullable(); // Sumber data (misal: Antam, Pegadaian)
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('uuid');
            $table->index('tanggal');
            $table->index('is_active');
            $table->unique(['tanggal', 'is_active']); // 1 harga aktif per tanggal
        });

        // Tabel 5: log_aktivitas
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('pengguna_id')->nullable()
                ->constrained('pengguna')->onDelete('set null');
            $table->string('peran'); // Role user saat aktivitas (admin, bendahara, dll)
            $table->string('aktivitas'); // login, logout, create, update, delete, approve, reject
            $table->string('modul'); // masjid, zakat, mustahik, transaksi, dll
            $table->text('deskripsi')->nullable();
            $table->json('data_lama')->nullable(); // Data sebelum perubahan (untuk audit)
            $table->json('data_baru')->nullable(); // Data setelah perubahan
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('uuid');
            $table->index('pengguna_id');
            $table->index('peran');
            $table->index('aktivitas');
            $table->index('modul');
            $table->index('created_at');
        });

        // Tabel 6: view_laporan_konsolidasi
        Schema::create('view_laporan_konsolidasi', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('masjid_id')->constrained('masjid')->onDelete('cascade');
            $table->integer('tahun');
            $table->integer('bulan');
            $table->decimal('total_penerimaan', 20, 2)->default(0);
            $table->decimal('total_penyaluran', 20, 2)->default(0);
            $table->decimal('saldo_akhir', 20, 2)->default(0);
            $table->integer('jumlah_muzakki')->default(0); // Jumlah pemberi zakat
            $table->integer('jumlah_mustahik')->default(0); // Jumlah penerima zakat
            $table->timestamps();

            $table->index('uuid');
            $table->index('masjid_id');
            $table->index(['tahun', 'bulan']);
            $table->unique(['masjid_id', 'tahun', 'bulan']); // Unik per masjid per bulan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('view_laporan_konsolidasi');
        Schema::dropIfExists('log_aktivitas');
        Schema::dropIfExists('harga_emas_perak');
        Schema::dropIfExists('kategori_mustahik');
        Schema::dropIfExists('tipe_zakat'); // Drop child dulu
        Schema::dropIfExists('jenis_zakat'); // Baru parent
    }
};