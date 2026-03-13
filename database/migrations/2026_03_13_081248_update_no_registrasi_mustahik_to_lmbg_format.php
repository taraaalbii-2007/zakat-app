<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Update kode_lembaga ─────────────────────────────────────────
        $lembagas = DB::table('lembaga')->get();

        foreach ($lembagas as $lembaga) {
            if (str_starts_with($lembaga->kode_lembaga, 'MSJ')) {
                // MSJ20260001 → LMBG20260001
                $newKode = 'LMBG' . substr($lembaga->kode_lembaga, 3);

                DB::table('lembaga')
                    ->where('id', $lembaga->id)
                    ->update(['kode_lembaga' => $newKode]);
            }
        }

        // ── 2. Update no_registrasi mustahik ───────────────────────────────
        $mustahiks = DB::table('mustahik')->get();

        foreach ($mustahiks as $mustahik) {
            if (!$mustahik->no_registrasi) continue;

            if (preg_match('/^MUST-MSJ(\d+)-(\d+)$/', $mustahik->no_registrasi, $matches)) {
                // MUST-MSJ20260003-001 → MUST-LMBG20260003-001
                $newNoReg = 'MUST-LMBG' . $matches[1] . '-' . $matches[2];

                DB::table('mustahik')
                    ->where('id', $mustahik->id)
                    ->update(['no_registrasi' => $newNoReg]);
            }
        }
    }

    public function down(): void
    {
        // ── 1. Rollback kode_lembaga ───────────────────────────────────────
        $lembagas = DB::table('lembaga')->get();

        foreach ($lembagas as $lembaga) {
            if (str_starts_with($lembaga->kode_lembaga, 'LMBG')) {
                // LMBG20260001 → MSJ20260001
                $oldKode = 'MSJ' . substr($lembaga->kode_lembaga, 4);

                DB::table('lembaga')
                    ->where('id', $lembaga->id)
                    ->update(['kode_lembaga' => $oldKode]);
            }
        }

        // ── 2. Rollback no_registrasi mustahik ────────────────────────────
        $mustahiks = DB::table('mustahik')->get();

        foreach ($mustahiks as $mustahik) {
            if (!$mustahik->no_registrasi) continue;

            if (preg_match('/^MUST-LMBG(\d+)-(\d+)$/', $mustahik->no_registrasi, $matches)) {
                // MUST-LMBG20260003-001 → MUST-MSJ20260003-001
                $oldNoReg = 'MUST-MSJ' . $matches[1] . '-' . $matches[2];

                DB::table('mustahik')
                    ->where('id', $mustahik->id)
                    ->update(['no_registrasi' => $oldNoReg]);
            }
        }
    }
};