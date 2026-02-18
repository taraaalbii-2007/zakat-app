<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel: transaksi_penyaluran
     * Deskripsi: Mencatat seluruh transaksi penyaluran zakat kepada mustahik.
     * Dikelola oleh: Amil
     * Disetujui oleh: Admin Masjid
     *
     * Changelog:
     * v2 - Penambahan & perbaikan:
     *   - Tambah kolom `disalurkan_at` untuk audit trail status disalurkan
     *   - Tambah kolom `dibatalkan_at` dan `dibatalkan_oleh` untuk audit trail pembatalan
     *   - Tambah kolom `periode` untuk mendukung zakat fitrah / zakat periodik
     *   - Rename `tanda_tangan_digital` → `path_tanda_tangan` (simpan path file, bukan base64)
     *   - Hapus `foto_dokumentasi` JSON → dipindah ke tabel `dokumentasi_penyaluran`
     *   - Tambah index pada `program_zakat_id`
     *   - Tambah index pada `periode`
     *   - Tambah index composite `[status, masjid_id]` untuk query approval
     *   - Tambah index composite `[mustahik_id, tanggal_penyaluran]` untuk riwayat mustahik
     */
    public function up(): void
    {
        Schema::create('transaksi_penyaluran', function (Blueprint $table) {

            // -------------------------
            // IDENTITAS RECORD
            // -------------------------
            $table->id();
            $table->uuid('uuid')->unique()->comment('UUID untuk ekspos ke API/publik, menghindari enumerable ID');
            $table->string('no_transaksi')->unique()->comment('Nomor transaksi auto-generate, human-readable');
            $table->string('no_kwitansi')->nullable()->comment('Nomor kwitansi fisik, bisa berbeda dari no_transaksi');

            // -------------------------
            // WAKTU & PERIODE
            // -------------------------
            $table->date('tanggal_penyaluran')->comment('Tanggal realisasi penyaluran');
            $table->time('waktu_penyaluran')->nullable()->comment('Waktu penyaluran jika dicatat');
            $table->string('periode', 7)->nullable()->comment('Periode zakat format YYYY-MM, untuk zakat fitrah/periodik. Contoh: 2024-03');

            // -------------------------
            // RELASI ENTITAS UTAMA (wajib, restrict delete)
            // -------------------------
            $table->foreignId('masjid_id')
                ->constrained('masjid')
                ->onDelete('restrict')
                ->comment('Masjid pengelola penyaluran');

            $table->foreignId('mustahik_id')
                ->constrained('mustahik')
                ->onDelete('restrict')
                ->comment('Mustahik penerima zakat');

            $table->foreignId('kategori_mustahik_id')
                ->constrained('kategori_mustahik')
                ->onDelete('restrict')
                ->comment('Kategori mustahik saat transaksi (snapshot, karena kategori bisa berubah). Harus konsisten dengan kategori mustahik_id.');

            // -------------------------
            // RELASI ENTITAS PENDUKUNG (nullable, set null saat dihapus)
            // -------------------------
            $table->foreignId('jenis_zakat_id')
                ->nullable()
                ->constrained('jenis_zakat')
                ->onDelete('set null')
                ->comment('Jenis zakat yang disalurkan. Nullable untuk penyaluran infaq/sedekah umum');

            $table->foreignId('program_zakat_id')
                ->nullable()
                ->constrained('program_zakat')
                ->onDelete('set null')
                ->comment('Program zakat terkait. Opsional');

            $table->foreignId('amil_id')
                ->nullable()
                ->constrained('amil')
                ->onDelete('set null')
                ->comment('Amil yang melakukan penyaluran');

            // -------------------------
            // NOMINAL & METODE PENYALURAN
            // -------------------------
            $table->decimal('jumlah', 20, 2)
                ->comment('Jumlah uang yang disalurkan (untuk metode tunai/transfer). Nilai bersih TANPA potongan apapun sesuai syariah.');

            $table->enum('metode_penyaluran', ['tunai', 'transfer', 'barang'])
                ->comment('Metode penyaluran: tunai = uang cash, transfer = bank/e-wallet, barang = in-kind');

            // Kolom khusus metode = barang
            $table->text('detail_barang')
                ->nullable()
                ->comment('Deskripsi detail barang yang disalurkan. Wajib diisi jika metode_penyaluran = barang');

            $table->decimal('nilai_barang', 20, 2)
                ->nullable()
                ->comment('Nilai estimasi barang dalam rupiah. Wajib diisi jika metode_penyaluran = barang');

            // -------------------------
            // DOKUMENTASI & BUKTI
            // -------------------------
            $table->string('foto_bukti')
                ->nullable()
                ->comment('Path file foto utama bukti penyerahan');

            // CATATAN: foto_dokumentasi (multiple) dipindah ke tabel dokumentasi_penyaluran
            // untuk memudahkan query, manajemen per-foto, dan penambahan metadata

            $table->string('path_tanda_tangan')
                ->nullable()
                ->comment('Path file tanda tangan digital mustahik (PNG/SVG). BUKAN base64 — simpan file, kolom hanya path');

            $table->text('keterangan')
                ->nullable()
                ->comment('Catatan tambahan terkait penyaluran');

            // -------------------------
            // WORKFLOW STATUS
            // -------------------------
            /*
             * ╔══════════════════════════════════════════════════════════════╗
             * ║                   ALUR STATUS TRANSAKSI                     ║
             * ╠══════════════════════════════════════════════════════════════╣
             * ║                                                              ║
             * ║   [AMIL] Input data → status: DRAFT                         ║
             * ║              │                                               ║
             * ║              ▼                                               ║
             * ║   [ADMIN MASJID] Review transaksi draft:                    ║
             * ║              │                                               ║
             * ║        ┌─────┴──────┐                                       ║
             * ║        ▼            ▼                                        ║
             * ║    SETUJU        TOLAK                                       ║
             * ║        │            │                                        ║
             * ║        ▼            ▼                                        ║
             * ║   DISETUJUI     DIBATALKAN ◄─── wajib isi alasan_pembatalan ║
             * ║        │                                                     ║
             * ║        ▼                                                     ║
             * ║   [AMIL] Konfirmasi sudah diserahkan ke mustahik             ║
             * ║        │                                                     ║
             * ║        ▼                                                     ║
             * ║   DISALURKAN  (final, tidak bisa diubah lagi)               ║
             * ║                                                              ║
             * ╠══════════════════════════════════════════════════════════════╣
             * ║  ATURAN PENTING:                                             ║
             * ║  • Hanya ADMIN MASJID yang boleh approve/reject              ║
             * ║  • Admin hanya bisa review transaksi di masjid sendiri       ║
             * ║  • Amil TIDAK bisa approve transaksinya sendiri              ║
             * ║  • Reject WAJIB disertai alasan_pembatalan (required)        ║
             * ║                                                              ║
             * ║  CATATAN SYARIAH:                                            ║
             * ║  • Kolom `jumlah` = nilai PENUH yang diterima mustahik       ║
             * ║  • TANPA potongan pajak / administrasi apapun                ║
             * ║  • Biaya transfer/operasional ditanggung dari dana amil      ║
             * ╚══════════════════════════════════════════════════════════════╝
             */
            $table->enum('status', ['draft', 'disetujui', 'disalurkan', 'dibatalkan'])
                ->default('draft')
                ->comment('
                    Status workflow transaksi penyaluran.
                    draft      = Amil sudah input, menunggu review Admin Masjid.
                    disetujui  = Admin Masjid menyetujui, siap disalurkan ke mustahik.
                    disalurkan = Amil konfirmasi sudah diserahkan ke mustahik (final).
                    dibatalkan = Admin Masjid menolak / transaksi dibatalkan, wajib ada alasan_pembatalan.
                ');

            $table->text('alasan_pembatalan')
                ->nullable()
                ->comment('
                    Wajib diisi saat status = dibatalkan (reject oleh Admin Masjid).
                    Validasi: required_if:status,dibatalkan.
                    Contoh isi: "Data mustahik tidak valid", "Nominal melebihi plafon program".
                ');

            // -------------------------
            // AUDIT TRAIL - PERSETUJUAN (oleh Admin Masjid)
            // -------------------------
            /*
             * Diisi HANYA saat Admin Masjid menekan tombol "Setuju / Approve".
             * Status berubah: draft → disetujui.
             *
             * Implementasi di controller:
             *   $transaksi->update([
             *       'status'      => 'disetujui',
             *       'approved_by' => auth()->id(),   // harus role admin_masjid
             *       'approved_at' => now(),
             *   ]);
             *
             * Policy yang harus dicek:
             *   - auth()->user()->role === 'admin_masjid'
             *   - auth()->user()->masjid_id === $transaksi->masjid_id
             *   - $transaksi->status === 'draft'
             */
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('pengguna')
                ->onDelete('set null')
                ->comment('ID Admin Masjid yang menekan tombol Approve. NULL jika belum/tidak disetujui.');

            $table->timestamp('approved_at')
                ->nullable()
                ->comment('Timestamp saat Admin Masjid menyetujui. Digunakan untuk timeline & laporan kecepatan approval.');

            // -------------------------
            // AUDIT TRAIL - PENYALURAN (oleh Amil)
            // -------------------------
            /*
             * Diisi HANYA saat Amil mengkonfirmasi bahwa barang/uang sudah
             * benar-benar diserahkan ke tangan mustahik.
             * Status berubah: disetujui → disalurkan.
             *
             * Implementasi di controller:
             *   $transaksi->update([
             *       'status'           => 'disalurkan',
             *       'disalurkan_oleh'  => auth()->id(),   // harus role amil
             *       'disalurkan_at'    => now(),
             *   ]);
             *
             * Policy yang harus dicek:
             *   - auth()->user()->role === 'amil'
             *   - $transaksi->status === 'disetujui'
             */
            $table->foreignId('disalurkan_oleh')
                ->nullable()
                ->constrained('pengguna')
                ->onDelete('set null')
                ->comment('ID Amil yang mengkonfirmasi penyaluran sudah diterima mustahik. NULL jika belum disalurkan.');

            $table->timestamp('disalurkan_at')
                ->nullable()
                ->comment('Timestamp saat Amil konfirmasi penyaluran selesai. Digunakan untuk timeline, laporan harian & bulanan.');

            // -------------------------
            // AUDIT TRAIL - PEMBATALAN/REJECT (oleh Admin Masjid)
            // -------------------------
            /*
             * Diisi HANYA saat Admin Masjid menekan tombol "Tolak / Reject".
             * Status berubah: draft → dibatalkan.
             *
             * PERBEDAAN dengan approved_by:
             *   - approved_by → Admin Masjid SETUJU
             *   - dibatalkan_oleh → Admin Masjid TOLAK
             * Keduanya dilakukan oleh role yang sama (admin_masjid),
             * tapi menghasilkan status yang berbeda dan kolom yang berbeda.
             *
             * Implementasi di controller:
             *   $transaksi->update([
             *       'status'             => 'dibatalkan',
             *       'alasan_pembatalan'  => $request->alasan,  // required!
             *       'dibatalkan_oleh'    => auth()->id(),       // harus role admin_masjid
             *       'dibatalkan_at'      => now(),
             *   ]);
             *
             * Policy yang harus dicek:
             *   - auth()->user()->role === 'admin_masjid'
             *   - auth()->user()->masjid_id === $transaksi->masjid_id
             *   - $transaksi->status === 'draft'
             *   - $request->alasan tidak boleh kosong
             */
            $table->foreignId('dibatalkan_oleh')
                ->nullable()
                ->constrained('pengguna')
                ->onDelete('set null')
                ->comment('ID Admin Masjid yang menekan tombol Reject/Tolak. NULL jika tidak dibatalkan.');

            $table->timestamp('dibatalkan_at')
                ->nullable()
                ->comment('Timestamp saat Admin Masjid menolak transaksi. Wajib ada alasan_pembatalan jika kolom ini terisi.');

            // -------------------------
            // TIMESTAMPS & SOFT DELETE
            // -------------------------
            $table->timestamps();
            $table->softDeletes()->comment('Soft delete — data historis keuangan tidak boleh dihapus permanen');

            // -------------------------
            // INDEXES
            // -------------------------

            // Composite: laporan periodik per masjid (query paling umum)
            $table->index(['masjid_id', 'tanggal_penyaluran'], 'idx_masjid_tanggal');

            // Composite: approval queue — filter pending per masjid
            $table->index(['status', 'masjid_id'], 'idx_status_masjid');

            // Composite: riwayat penyaluran per mustahik
            $table->index(['mustahik_id', 'tanggal_penyaluran'], 'idx_mustahik_tanggal');

            // Single indexes untuk filter & join
            $table->index('kategori_mustahik_id', 'idx_kategori_mustahik');
            $table->index('jenis_zakat_id', 'idx_jenis_zakat');
            $table->index('program_zakat_id', 'idx_program_zakat');   // ← sebelumnya tidak ada
            $table->index('amil_id', 'idx_amil');
            $table->index('status', 'idx_status');
            $table->index('periode', 'idx_periode');                   // ← sebelumnya tidak ada
            $table->index('tanggal_penyaluran', 'idx_tanggal');        // untuk summary harian
        });

        // =========================================================
        // TABEL PENDUKUNG: dokumentasi_penyaluran
        // Memisahkan foto_dokumentasi (sebelumnya JSON) ke tabel
        // tersendiri agar tiap foto bisa dikelola secara individual
        // =========================================================
        Schema::create('dokumentasi_penyaluran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_penyaluran_id')
                ->constrained('transaksi_penyaluran')
                ->onDelete('cascade')
                ->comment('Transaksi induk. Hapus transaksi → hapus semua foto dokumentasinya');

            $table->string('path_foto')->comment('Path file foto dokumentasi');
            $table->string('keterangan_foto')->nullable()->comment('Deskripsi singkat foto ini');
            $table->unsignedTinyInteger('urutan')->default(0)->comment('Urutan tampil foto, 0 = pertama');
            $table->timestamps();

            $table->index('transaksi_penyaluran_id', 'idx_dok_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_penyaluran');
        Schema::dropIfExists('transaksi_penyaluran');
    }
};