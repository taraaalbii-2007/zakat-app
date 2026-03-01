<?php
// database/migrations/xxxx_xx_xx_add_ip_address_to_kontaks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kontak', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('user_id');
            $table->text('user_agent')->nullable()->after('ip_address');
        });
    }

    public function down()
    {
        Schema::table('kontak', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'user_agent']);
        });
    }
};