<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bulletins', function (Blueprint $table) {
            // Lembaga yang membuat bulletin (null = superadmin)
            $table->unsignedBigInteger('lembaga_id')->nullable()->after('created_by');

            // Status approval: draft | pending | approved | rejected
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])
                  ->default('draft')
                  ->after('lembaga_id');

            // Alasan penolakan dari superadmin
            $table->text('rejection_reason')->nullable()->after('status');

            // Waktu approval/rejection
            $table->timestamp('reviewed_at')->nullable()->after('rejection_reason');

            // Siapa yang mereview (superadmin)
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('reviewed_at');

            $table->foreign('lembaga_id')->references('id')->on('lembaga')->nullOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('pengguna')->nullOnDelete();
        });

        // Update bulletin lama milik superadmin menjadi approved
        DB::table('bulletins')->whereNull('lembaga_id')->update(['status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('bulletins', function (Blueprint $table) {
            $table->dropForeign(['lembaga_id']);
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['lembaga_id', 'status', 'rejection_reason', 'reviewed_at', 'reviewed_by']);
        });
    }
};