<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $amils = DB::table('amil')->get();

        foreach ($amils as $amil) {
            if (!$amil->kode_amil) continue;

            if (preg_match('/^AMIL-MSJ(\d+)-(\d+)$/', $amil->kode_amil, $matches)) {
                // AMIL-MSJ20260003-001 → AMIL-LMBG20260003-001
                $newKode = 'AMIL-LMBG' . $matches[1] . '-' . $matches[2];

                DB::table('amil')
                    ->where('id', $amil->id)
                    ->update(['kode_amil' => $newKode]);
            }
        }
    }

    public function down(): void
    {
        $amils = DB::table('amil')->get();

        foreach ($amils as $amil) {
            if (!$amil->kode_amil) continue;

            if (preg_match('/^AMIL-LMBG(\d+)-(\d+)$/', $amil->kode_amil, $matches)) {
                // AMIL-LMBG20260003-001 → AMIL-MSJ20260003-001
                $oldKode = 'AMIL-MSJ' . $matches[1] . '-' . $matches[2];

                DB::table('amil')
                    ->where('id', $amil->id)
                    ->update(['kode_amil' => $oldKode]);
            }
        }
    }
};