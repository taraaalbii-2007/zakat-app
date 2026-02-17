<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Superadmin\KonfigurasiGlobalController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('konfigurasi_global')->name('konfigurasi-global.')->group(function () {
    Route::get('/', [KonfigurasiGlobalController::class, 'show'])->name('show');
    Route::get('/edit', [KonfigurasiGlobalController::class, 'edit'])->name('edit');
    Route::put('/update', [KonfigurasiGlobalController::class, 'update'])->name('update');
    Route::delete('/logo/hapus', [KonfigurasiGlobalController::class, 'hapusLogo'])->name('logo.hapus');
    Route::delete('/favicon/hapus', [KonfigurasiGlobalController::class, 'hapusFavicon'])->name('favicon.hapus');
});

