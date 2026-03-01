<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kontak', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama', 255);
            $table->string('email', 255);
            $table->string('subjek', 255);
            $table->longText('pesan');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->dateTime('dibaca_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('pengguna')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kontak');
    }
};