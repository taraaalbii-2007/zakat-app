<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
   public function up(): void
{
    $lembagas = DB::table('lembaga')->get();

    foreach ($lembagas as $lembaga) {
        if (str_starts_with($lembaga->kode_lembaga, 'MSJ')) {
            // MSJ20260001 → LMBG-2026-0001
            $year = substr($lembaga->kode_lembaga, 3, 4);   // ambil 4 digit tahun
            $number = substr($lembaga->kode_lembaga, -4);    // ambil 4 digit nomor
            
            $newKode = 'LMBG-' . $year . '-' . $number;
            
            DB::table('lembaga')
                ->where('id', $lembaga->id)
                ->update(['kode_lembaga' => $newKode]);
        }
    }
}

public function down(): void
{
    $lembagas = DB::table('lembaga')->get();

    foreach ($lembagas as $lembaga) {
        if (str_starts_with($lembaga->kode_lembaga, 'LMBG-')) {
            // LMBG-2026-0001 → MSJ20260001
            $parts = explode('-', $lembaga->kode_lembaga); // ['LMBG', '2026', '0001']
            $oldKode = 'MSJ' . $parts[1] . $parts[2];
            
            DB::table('lembaga')
                ->where('id', $lembaga->id)
                ->update(['kode_lembaga' => $oldKode]);
        }
    }
}
};