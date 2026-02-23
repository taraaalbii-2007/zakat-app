<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_penerimaan', function (Blueprint $table) {
            // Jumlah yang benar-benar dibayar (bisa lebih dari jumlah zakat)
            $table->decimal('jumlah_dibayar', 20, 2)->nullable()
                ->after('jumlah')
                ->comment('Nominal aktual yang dibayar. Bisa > jumlah zakat jika ada kelebihan (infaq)');

            // Kelebihan bayar otomatis jadi infaq
            $table->decimal('jumlah_infaq', 20, 2)->nullable()->default(0)
                ->after('jumlah_dibayar')
                ->comment('Kelebihan bayar yang dianggap sebagai infaq sukarela');

            // Apakah kelebihan sudah dipisahkan sebagai infaq
            $table->boolean('has_infaq')->default(false)
                ->after('jumlah_infaq');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_penerimaan', function (Blueprint $table) {
            $table->dropColumn(['jumlah_dibayar', 'jumlah_infaq', 'has_infaq']);
        });
    }
};