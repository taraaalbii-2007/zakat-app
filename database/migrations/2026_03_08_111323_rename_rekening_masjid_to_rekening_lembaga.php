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
        Schema::rename('rekening_masjid', 'rekening_lembaga');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('rekening_lembaga', 'rekening_masjid');
    }
};