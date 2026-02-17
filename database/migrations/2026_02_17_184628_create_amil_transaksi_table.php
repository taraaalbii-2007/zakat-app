<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * CATATAN SYARIAT:
     * Midtrans memotong MDR dari setiap transaksi sehingga zakat tidak sampai 100% ke mustahik.
     * Ini MELANGGAR syariat. Sistem ini menggunakan konfirmasi manual:
     * - Tunai    → langsung verified oleh amil
     * - Transfer → muzakki transfer ke rekening masjid sendiri, amil konfirmasi manual
     * - QRIS     → muzakki scan QRIS statis milik masjid, amil konfirmasi manual
     */
    public function up(): void
    {
        Schema::create('transaksi_penerimaan', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('no_transaksi')->unique();
            $table->date('tanggal_transaksi');
            $table->time('waktu_transaksi')->nullable();

            $table->foreignId('masjid_id')->constrained('masjid')->onDelete('restrict');
            $table->foreignId('jenis_zakat_id')->nullable()->constrained('jenis_zakat')->onDelete('restrict');
            $table->foreignId('tipe_zakat_id')->nullable()->constrained('tipe_zakat')->onDelete('set null');
            $table->foreignId('program_zakat_id')->nullable()->constrained('program_zakat')->onDelete('set null');

            $table->string('muzakki_nama');
            $table->string('muzakki_telepon')->nullable();
            $table->string('muzakki_email')->nullable();
            $table->text('muzakki_alamat')->nullable();
            $table->string('muzakki_nik', 16)->nullable();

            $table->enum('metode_penerimaan', ['datang_langsung', 'dijemput'])->default('datang_langsung');
            $table->foreignId('amil_id')->nullable()->constrained('amil')->onDelete('set null');

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Status penjemputan
            $table->enum('status_penjemputan', [
                'menunggu', 'diterima', 'dalam_perjalanan', 'sampai_lokasi', 'selesai'
            ])->nullable();
            $table->timestamp('waktu_request')->nullable();
            $table->timestamp('waktu_diterima_amil')->nullable();
            $table->timestamp('waktu_berangkat')->nullable();
            $table->timestamp('waktu_sampai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();

            $table->decimal('jumlah', 20, 2)->nullable();

            /**
             * Metode pembayaran — TANPA payment gateway:
             * tunai    → langsung verified
             * transfer → muzakki transfer sendiri ke rekening masjid, amil upload/konfirmasi
             * qris     → muzakki scan QRIS statis masjid, amil konfirmasi manual
             */
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'qris'])->nullable();

            /**
             * Konfirmasi manual (hanya untuk transfer & qris):
             * - menunggu_konfirmasi → muzakki sudah transfer/scan, menunggu amil cek
             * - dikonfirmasi        → amil sudah cek, dana masuk
             * - ditolak             → bukti tidak valid / dana tidak masuk
             */
            $table->enum('konfirmasi_status', [
                'menunggu_konfirmasi',
                'dikonfirmasi',
                'ditolak'
            ])->nullable(); // null = tunai (tidak perlu konfirmasi)

            $table->string('no_referensi_transfer')->nullable(); // no. ref dari slip transfer
            $table->foreignId('dikonfirmasi_oleh')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->timestamp('konfirmasi_at')->nullable();
            $table->text('catatan_konfirmasi')->nullable();

            // Detail Zakat Fitrah
            $table->integer('jumlah_jiwa')->nullable();
            $table->decimal('nominal_per_jiwa', 15, 2)->nullable();
            $table->decimal('jumlah_beras_kg', 10, 2)->nullable();
            $table->decimal('harga_beras_per_kg', 15, 2)->nullable();

            // Detail Zakat Mal
            $table->decimal('nilai_harta', 20, 2)->nullable();
            $table->decimal('nisab_saat_ini', 20, 2)->nullable();
            $table->boolean('sudah_haul')->nullable();
            $table->date('tanggal_mulai_haul')->nullable();

            // Bukti
            $table->string('no_kwitansi')->nullable();
            $table->string('bukti_transfer')->nullable(); // bukti transfer bank ATAU screenshot QRIS
            $table->json('foto_dokumentasi')->nullable();

            $table->text('keterangan')->nullable();

            // Status verifikasi
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('alasan_penolakan')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            $table->index(['masjid_id', 'tanggal_transaksi']);
            $table->index('jenis_zakat_id');
            $table->index('tipe_zakat_id');
            $table->index('amil_id');
            $table->index('muzakki_nama');
            $table->index('metode_penerimaan');
            $table->index('status_penjemputan');
            $table->index('status');
            $table->index('konfirmasi_status');
        });

        Schema::create('transaksi_penyaluran', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('no_transaksi')->unique();
            $table->date('tanggal_penyaluran');
            $table->time('waktu_penyaluran')->nullable();
            $table->foreignId('masjid_id')->constrained('masjid')->onDelete('restrict');
            $table->foreignId('mustahik_id')->constrained('mustahik')->onDelete('restrict');
            $table->foreignId('kategori_mustahik_id')->constrained('kategori_mustahik')->onDelete('restrict');
            $table->foreignId('jenis_zakat_id')->nullable()->constrained('jenis_zakat')->onDelete('set null');
            $table->foreignId('program_zakat_id')->nullable()->constrained('program_zakat')->onDelete('set null');
            $table->foreignId('amil_id')->nullable()->constrained('amil')->onDelete('set null');
            $table->decimal('jumlah', 20, 2);
            $table->enum('metode_penyaluran', ['tunai', 'transfer', 'barang']);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('detail_barang')->nullable();
            $table->decimal('nilai_barang', 20, 2)->nullable();
            $table->string('no_kwitansi')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->json('foto_dokumentasi')->nullable();
            $table->string('tanda_tangan_digital')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['draft', 'disetujui', 'disalurkan', 'dibatalkan'])->default('draft');
            $table->text('alasan_pembatalan')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['masjid_id', 'tanggal_penyaluran']);
            $table->index('mustahik_id');
            $table->index('kategori_mustahik_id');
            $table->index('jenis_zakat_id');
            $table->index('amil_id');
            $table->index('status');
        });

        Schema::create('kas_harian_amil', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('amil_id')->constrained('amil')->onDelete('cascade');
            $table->foreignId('masjid_id')->constrained('masjid')->onDelete('cascade');
            $table->date('tanggal');
            $table->decimal('saldo_awal', 20, 2)->default(0);
            $table->decimal('total_penerimaan', 20, 2)->default(0);
            $table->decimal('total_penyaluran', 20, 2)->default(0);
            $table->decimal('saldo_akhir', 20, 2)->default(0);
            $table->integer('jumlah_transaksi_masuk')->default(0);
            $table->integer('jumlah_transaksi_keluar')->default(0);
            $table->integer('jumlah_penjemputan')->default(0);
            $table->integer('jumlah_datang_langsung')->default(0);
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamp('closed_at')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->index(['amil_id', 'tanggal']);
            $table->index('masjid_id');
            $table->index('status');
            $table->unique(['amil_id', 'tanggal']);
        });

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
            $table->timestamps();
            $table->index(['amil_id', 'tanggal_setor']);
            $table->index('masjid_id');
            $table->index('status');
        });

        Schema::create('kunjungan_mustahik', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('amil_id')->constrained('amil')->onDelete('cascade');
            $table->foreignId('mustahik_id')->constrained('mustahik')->onDelete('cascade');
            $table->date('tanggal_kunjungan');
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->enum('tujuan', ['verifikasi', 'penyaluran', 'monitoring', 'lainnya']);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('hasil_kunjungan')->nullable();
            $table->json('foto_dokumentasi')->nullable();
            $table->enum('status', ['direncanakan', 'selesai', 'dibatalkan'])->default('direncanakan');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->index(['amil_id', 'tanggal_kunjungan']);
            $table->index('mustahik_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan_mustahik');
        Schema::dropIfExists('setor_kas');
        Schema::dropIfExists('kas_harian_amil');
        Schema::dropIfExists('transaksi_penyaluran');
        Schema::dropIfExists('transaksi_penerimaan');
    }
};