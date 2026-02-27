<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_penerimaan', function (Blueprint $table) {

            // ── Kolom umum fidyah ─────────────────────────────────────
            $table->unsignedSmallInteger('fidyah_jumlah_hari')
                ->nullable()
                ->after('harga_beras_per_kg')
                ->comment('Jumlah hari puasa yang ditinggalkan (semua tipe fidyah)');

            // ── Fidyah Bahan Pokok Mentah ─────────────────────────────
            $table->string('fidyah_nama_bahan', 100)
                ->nullable()
                ->after('fidyah_jumlah_hari')
                ->comment('Nama bahan pokok: beras, gandum, dll');

            $table->unsignedSmallInteger('fidyah_berat_per_hari_gram')
                ->nullable()
                ->default(675)
                ->after('fidyah_nama_bahan')
                ->comment('Berat per hari dalam gram (default 675 = 1 mud)');

            $table->decimal('fidyah_total_berat_kg', 8, 3)
                ->nullable()
                ->after('fidyah_berat_per_hari_gram')
                ->comment('Total berat bahan pokok dalam kg');

            // ── Fidyah Makanan Matang ─────────────────────────────────
            $table->unsignedSmallInteger('fidyah_jumlah_box')
                ->nullable()
                ->after('fidyah_total_berat_kg')
                ->comment('Jumlah box/porsi makanan siap santap');

            $table->string('fidyah_menu_makanan', 200)
                ->nullable()
                ->after('fidyah_jumlah_box')
                ->comment('Jenis/menu makanan matang');

            $table->decimal('fidyah_harga_per_box', 12, 2)
                ->nullable()
                ->after('fidyah_menu_makanan')
                ->comment('Harga per box opsional');

            $table->enum('fidyah_cara_serah', ['dibagikan', 'dijamu', 'via_lembaga'])
                ->nullable()
                ->after('fidyah_harga_per_box')
                ->comment('Cara penyerahan makanan matang');

            // ── Tipe fidyah (untuk filter & laporan) ─────────────────
            $table->enum('fidyah_tipe', ['mentah', 'matang', 'tunai'])
                ->nullable()
                ->after('fidyah_cara_serah')
                ->comment('Tipe fidyah: mentah | matang | tunai');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_penerimaan', function (Blueprint $table) {
            $table->dropColumn([
                'fidyah_jumlah_hari',
                'fidyah_nama_bahan',
                'fidyah_berat_per_hari_gram',
                'fidyah_total_berat_kg',
                'fidyah_jumlah_box',
                'fidyah_menu_makanan',
                'fidyah_harga_per_box',
                'fidyah_cara_serah',
                'fidyah_tipe',
            ]);
        });
    }
};