<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mustahik', function (Blueprint $table) {
            $table->dropColumn([
                'provinsi_kode',
                'kota_kode',
                'kecamatan_kode',
                'kelurahan_kode'
            ]);
        });
    }

    public function down()
    {
        Schema::table('mustahik', function (Blueprint $table) {
            $table->string('provinsi_kode', 2)->nullable()->after('alamat');
            $table->string('kota_kode', 4)->nullable()->after('provinsi_kode');
            $table->string('kecamatan_kode', 10)->nullable()->after('kota_kode');
            $table->string('kelurahan_kode', 13)->nullable()->after('kecamatan_kode');
        });
    }
};