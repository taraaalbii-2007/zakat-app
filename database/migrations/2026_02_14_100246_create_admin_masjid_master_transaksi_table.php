<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel relasi pengguna sebagai amil
        Schema::create('amil', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->foreignId('masjid_id')->constrained('masjid')->onDelete('cascade');
   
            // Data pribadi amil (ditambahkan)
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('telepon');
            $table->string('email')->unique();
            $table->string('foto')->nullable();

            // Data tambahan amil
            $table->string('kode_amil')->unique();
            $table->date('tanggal_mulai_tugas');
            $table->date('tanggal_selesai_tugas')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'cuti'])->default('aktif');
            $table->text('keterangan')->nullable();

            // Wilayah tugas
            $table->string('wilayah_tugas')->nullable();

            $table->timestamps();

            // Indeks
            $table->index('uuid');
            $table->index('pengguna_id');
            $table->index('masjid_id');
            $table->index('kode_amil');
            $table->index('status');
            $table->index('email');
            $table->index('nama_lengkap');
        });

        // Tabel mustahik
        Schema::create('mustahik', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('masjid_id')->constrained('masjid')->onDelete('cascade');
            $table->foreignId('kategori_mustahik_id')
                ->constrained('kategori_mustahik')->onDelete('restrict');

            // Data pribadi
            $table->string('no_registrasi')->unique();
            $table->string('nik', 16)->nullable();
            $table->string('kk', 16)->nullable();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('telepon')->nullable();
            $table->text('alamat');

            // Wilayah
            $table->char('provinsi_kode', 2)->nullable();
            $table->char('kota_kode', 4)->nullable();
            $table->char('kecamatan_kode', 10)->nullable();
            $table->char('kelurahan_kode', 13)->nullable();
            $table->string('rt_rw')->nullable();
            $table->string('kode_pos')->nullable();

            // Data sosial ekonomi
            $table->string('pekerjaan')->nullable();
            $table->decimal('penghasilan_perbulan', 15, 2)->nullable();
            $table->integer('jumlah_tanggungan')->default(0);
            $table->enum('status_rumah', ['milik_sendiri', 'kontrak', 'menumpang', 'lainnya'])->nullable();
            $table->text('kondisi_kesehatan')->nullable();
            $table->text('catatan')->nullable();

            // Dokumen pendukung
            $table->string('foto_ktp')->nullable();
            $table->string('foto_kk')->nullable();
            $table->string('foto_rumah')->nullable();
            $table->json('dokumen_lainnya')->nullable();

            // Status verifikasi
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])
                ->default('pending');
            $table->text('alasan_penolakan')->nullable();
            $table->foreignId('verified_by')->nullable()
                ->constrained('pengguna')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();

            // Status aktif
            $table->boolean('is_active')->default(true);
            $table->date('tanggal_registrasi');
            $table->date('tanggal_nonaktif')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('pengguna')->onDelete('set null');

            $table->timestamps();
            $table->index('uuid');
            $table->index('masjid_id');
            $table->index('kategori_mustahik_id');
            $table->index('no_registrasi');
            $table->index('nik');
            $table->index('nama_lengkap');
            $table->index('status_verifikasi');
            $table->index('is_active');
        });

        // Tabel program zakat
        Schema::create('program_zakat', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('masjid_id')->constrained('masjid')->onDelete('cascade');

            $table->string('nama_program');
            $table->string('kode_program')->unique();
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();

            // Target
            $table->decimal('target_dana', 20, 2)->nullable();
            $table->integer('target_mustahik')->nullable();

            // Realisasi
            $table->decimal('realisasi_dana', 20, 2)->default(0);
            $table->integer('realisasi_mustahik')->default(0);

            $table->enum('status', ['draft', 'aktif', 'selesai', 'dibatalkan'])
                ->default('draft');
            $table->text('catatan')->nullable();
            $table->json('foto_kegiatan')->nullable();

            $table->timestamps();
            $table->index('uuid');
            $table->index('masjid_id');
            $table->index('kode_program');
            $table->index('status');
            $table->index(['tanggal_mulai', 'tanggal_selesai']);
        });

        // Tabel rekening masjid
        Schema::create('rekening_masjid', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('masjid_id')->constrained('masjid')->onDelete('cascade');

            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->string('nama_pemilik');
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();

            $table->timestamps();
            $table->index('uuid');
            $table->index('masjid_id');
            $table->index('is_primary');
            $table->index('is_active');
        });

        // Tabel laporan keuangan masjid
        Schema::create('laporan_keuangan_masjid', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('masjid_id')->constrained('masjid')->onDelete('cascade');

            $table->integer('tahun');
            $table->integer('bulan');
            $table->date('periode_mulai');
            $table->date('periode_selesai');

            // Saldo
            $table->decimal('saldo_awal', 20, 2)->default(0);
            $table->decimal('total_penerimaan', 20, 2)->default(0);
            $table->decimal('total_penyaluran', 20, 2)->default(0);
            $table->decimal('saldo_akhir', 20, 2)->default(0);

            // Detail per jenis zakat
            $table->json('detail_penerimaan')->nullable();
            $table->json('detail_penyaluran')->nullable();

            // Statistik
            $table->integer('jumlah_muzakki')->default(0);
            $table->integer('jumlah_mustahik')->default(0);
            $table->integer('jumlah_transaksi_masuk')->default(0);
            $table->integer('jumlah_transaksi_keluar')->default(0);

            $table->enum('status', ['draft', 'final', 'published'])->default('draft');
            $table->foreignId('created_by')->nullable()
                ->constrained('pengguna')->onDelete('set null');
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->index('uuid');
            $table->index('masjid_id');
            $table->index(['tahun', 'bulan']);
            $table->index('status');
            $table->unique(['masjid_id', 'tahun', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_keuangan_masjid');
        Schema::dropIfExists('rekening_masjid');
        Schema::dropIfExists('program_zakat');
        Schema::dropIfExists('mustahik');
        Schema::dropIfExists('amil');
    }
};