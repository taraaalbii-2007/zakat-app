<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('transaksi_penerimaan', function (Blueprint $table) {
        if (Schema::hasColumn('transaksi_penerimaan', 'latitude')) {
            $table->dropColumn('latitude');
        }
        if (Schema::hasColumn('transaksi_penerimaan', 'longitude')) {
            $table->dropColumn('longitude');
        }
    });

    Schema::table('transaksi_penyaluran', function (Blueprint $table) {
        if (Schema::hasColumn('transaksi_penyaluran', 'latitude')) {
            $table->dropColumn('latitude');
        }
        if (Schema::hasColumn('transaksi_penyaluran', 'longitude')) {
            $table->dropColumn('longitude');
        }
    });
}
};