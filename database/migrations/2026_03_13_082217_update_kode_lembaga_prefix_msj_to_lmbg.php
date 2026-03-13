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
            if (str_starts_with($lembaga->kode_lembaga, 'LMBG-')) {
                // LMBG-2026-0001 → LMBG20260001
                $newKode = str_replace('-', '', $lembaga->kode_lembaga);

                DB::table('lembaga')
                    ->where('id', $lembaga->id)
                    ->update(['kode_lembaga' => $newKode]);
            }
        }

        // ── 2. Update no_registrasi mustahik ───────────────────────────────
        $mustahiks = DB::table('mustahik')->get();

        foreach ($mustahiks as $mustahik) {
            if (!$mustahik->no_registrasi) continue;

            if (preg_match('/^MUST-LMBG-(\d+)-(\d+)-(\d+)$/', $mustahik->no_registrasi, $matches)) {
                // MUST-LMBG-2026-0001-001 → MUST-LMBG20260001-001
                $newNoReg = 'MUST-LMBG' . $matches[1] . $matches[2] . '-' . $matches[3];

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
            if (preg_match('/^LMBG(\d{4})(\d{4})$/', $lembaga->kode_lembaga, $matches)) {
                // LMBG20260001 → LMBG-2026-0001
                $oldKode = 'LMBG-' . $matches[1] . '-' . $matches[2];

                DB::table('lembaga')
                    ->where('id', $lembaga->id)
                    ->update(['kode_lembaga' => $oldKode]);
            }
        }

        // ── 2. Rollback no_registrasi mustahik ────────────────────────────
        $mustahiks = DB::table('mustahik')->get();

        foreach ($mustahiks as $mustahik) {
            if (!$mustahik->no_registrasi) continue;

            if (preg_match('/^MUST-LMBG(\d{4})(\d{4})-(\d+)$/', $mustahik->no_registrasi, $matches)) {
                // MUST-LMBG20260001-001 → MUST-LMBG-2026-0001-001
                $oldNoReg = 'MUST-LMBG-' . $matches[1] . '-' . $matches[2] . '-' . $matches[3];

                DB::table('mustahik')
                    ->where('id', $mustahik->id)
                    ->update(['no_registrasi' => $oldNoReg]);
            }
        }
    }
};