<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Superadmin\KonfigurasiGlobalController;
use App\Http\Controllers\MasjidController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\WilayahController;
use App\Http\Controllers\Superadmin\JenisZakatController;
use App\Http\Controllers\Superadmin\KategoriMustahikController;
use App\Http\Controllers\Superadmin\HargaEmasPerakController;
use App\Http\Controllers\Superadmin\LogAktivitasController;
use App\Http\Controllers\Superadmin\LaporanKonsolidasiController;
use App\Http\Controllers\Superadmin\TipeZakatController;
use App\Http\Controllers\Admin_masjid\AmilController;
use App\Http\Controllers\Admin_masjid\MustahikController;
use App\Http\Controllers\Amil\TransaksiPenerimaanController;
use App\Http\Controllers\Amil\ProfilAmilController;

Route::get('/', function () {
    return view('welcome');
});

// ============================================
// AUTHENTICATION ROUTES (PUBLIC)
// ============================================

// Google OAuth Routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Guest Routes (tanpa auth)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('verify-otp');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp.submit');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend-otp');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{uuid}/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password/{uuid}', [AuthController::class, 'resetPassword'])->name('password.update');

    Route::get('/complete-profile/{token}', [AuthController::class, 'showCompleteProfile'])->name('complete-profile');
    Route::post('/complete-profile/{token}', [AuthController::class, 'storeCompleteProfile'])->name('complete-profile.store');

    Route::get('/reset-password-sent', [AuthController::class, 'showResetSent'])->name('password.reset-sent');
    Route::post('/resend-reset-link', [AuthController::class, 'resendResetLink'])->name('password.resend');
});

// Registration API Routes (bisa diakses tanpa auth)
Route::prefix('registration-api')->name('registration.api.')->group(function () {
    Route::get('/cities', [AuthController::class, 'getCities'])->name('cities');
    Route::get('/districts', [AuthController::class, 'getDistricts'])->name('districts');
    Route::get('/villages', [AuthController::class, 'getVillages'])->name('villages');
    Route::get('/postal-code', [AuthController::class, 'getPostalCode'])->name('postal-code');
});

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================
// PROTECTED ROUTES - DASHBOARD (SEMUA ROLE)
// ============================================
Route::middleware(['auth', 'active.user', 'masjid.access'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ============================================
// SUPERADMIN ROUTES
// ============================================
Route::middleware(['auth', 'active.user', 'superadmin'])->group(function () {
    // Konfigurasi Aplikasi (global)
    Route::prefix('konfigurasi-global')->name('konfigurasi-global.')->group(function () {
        Route::get('/', [KonfigurasiGlobalController::class, 'show'])->name('show');
        Route::get('/edit', [KonfigurasiGlobalController::class, 'edit'])->name('edit');
        Route::post('/update', [KonfigurasiGlobalController::class, 'update'])->name('update');
        Route::post('/reset', [KonfigurasiGlobalController::class, 'reset'])->name('reset');
        Route::delete('/hapus-logo', [KonfigurasiGlobalController::class, 'hapusLogo'])->name('hapus-logo');
        Route::delete('/hapus-favicon', [KonfigurasiGlobalController::class, 'hapusFavicon'])->name('hapus-favicon');
    });

    // Kelola Masjid
    Route::resource('masjid', MasjidController::class)->parameters([
        'masjid' => 'masjid:uuid'
    ]);

    Route::post('/masjid/{masjid}/fotos', [MasjidController::class, 'uploadFoto'])
        ->name('masjid.fotos.upload');

    Route::delete('/masjid/{masjid}/fotos/{index}', [MasjidController::class, 'deleteFoto'])
        ->name('masjid.fotos.delete');

    // Master Data Jenis Zakat (superadmin only)
    Route::prefix('jenis-zakat')->name('jenis-zakat.')->group(function () {
        Route::get('/', [JenisZakatController::class, 'index'])->name('index');
        Route::get('/create', [JenisZakatController::class, 'create'])->name('create');
        Route::post('/', [JenisZakatController::class, 'store'])->name('store');
        Route::get('/{uuid}/edit', [JenisZakatController::class, 'edit'])->name('edit');
        Route::put('/{uuid}', [JenisZakatController::class, 'update'])->name('update');
        Route::delete('/{uuid}', [JenisZakatController::class, 'destroy'])->name('destroy');
        Route::patch('/{uuid}/toggle-status', [JenisZakatController::class, 'toggleStatus'])->name('toggle-status');

        // API/Additional routes
        Route::get('/api/list', [JenisZakatController::class, 'getJenisZakat'])->name('api.list');
        Route::post('/bulk-update', [JenisZakatController::class, 'bulkUpdate'])->name('bulk-update');
    });

    // Master Data Kategori Mustahik (superadmin only)
    Route::prefix('kategori-mustahik')->name('kategori-mustahik.')->group(function () {
        Route::get('/', [KategoriMustahikController::class, 'index'])->name('index');
        Route::get('/create', [KategoriMustahikController::class, 'create'])->name('create');
        Route::post('/', [KategoriMustahikController::class, 'store'])->name('store');
        Route::get('/{kategoriMustahik}/edit', [KategoriMustahikController::class, 'edit'])->name('edit');
        Route::put('/{kategoriMustahik}', [KategoriMustahikController::class, 'update'])->name('update');
        Route::delete('/{kategoriMustahik}', [KategoriMustahikController::class, 'destroy'])->name('destroy');
        Route::patch('/{kategoriMustahik}/toggle-status', [KategoriMustahikController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Master Data Harga Emas Perak (superadmin only)
    Route::prefix('harga-emas-perak')->name('harga-emas-perak.')->group(function () {
        Route::get('/', [HargaEmasPerakController::class, 'index'])->name('index');
        Route::get('/create', [HargaEmasPerakController::class, 'create'])->name('create');
        Route::post('/', [HargaEmasPerakController::class, 'store'])->name('store');
        Route::get('/{uuid}/edit', [HargaEmasPerakController::class, 'edit'])->name('edit');
        Route::put('/{uuid}', [HargaEmasPerakController::class, 'update'])->name('update');
        Route::delete('/{uuid}', [HargaEmasPerakController::class, 'destroy'])->name('destroy');
        Route::patch('/{uuid}/toggle-status', [HargaEmasPerakController::class, 'toggleStatus'])->name('toggle-status');

        // API routes
        Route::get('/api/sumber-list', [HargaEmasPerakController::class, 'getSumberList'])->name('api.sumber-list');
    });


    Route::prefix('tipe-zakat')->name('tipe-zakat.')->group(function () {
        Route::get('/', [TipeZakatController::class, 'index'])->name('index');
        Route::get('/create', [TipeZakatController::class, 'create'])->name('create');
        Route::post('/', [TipeZakatController::class, 'store'])->name('store');
        Route::get('/{tipeZakat:uuid}/edit', [TipeZakatController::class, 'edit'])->name('edit');
        Route::put('/{tipeZakat:uuid}', [TipeZakatController::class, 'update'])->name('update');
        Route::delete('/{tipeZakat:uuid}', [TipeZakatController::class, 'destroy'])->name('destroy');

        // API routes
        Route::get('/api/by-jenis', [TipeZakatController::class, 'getByJenisZakat'])->name('api.by-jenis');
    });

    // Log Aktivitas (hanya superadmin yang lihat semua)
    Route::prefix('log-aktivitas')->name('log-aktivitas.')->group(function () {
        Route::get('/', [LogAktivitasController::class, 'index'])->name('index');
        Route::get('/{uuid}', [LogAktivitasController::class, 'show'])->name('show');
        Route::get('/export', [LogAktivitasController::class, 'export'])->name('export');
        Route::post('/cleanup', [LogAktivitasController::class, 'cleanup'])->name('cleanup');
    });

    // Laporan Konsolidasi Semua Masjid (superadmin only)
    Route::prefix('laporan-konsolidasi')->name('laporan-konsolidasi.')->group(function () {
        Route::get('/', [LaporanKonsolidasiController::class, 'index'])->name('index');
        Route::get('/{masjidId}', [LaporanKonsolidasiController::class, 'show'])->name('detail');
        Route::get('/{masjidId}/export', [LaporanKonsolidasiController::class, 'export'])->name('export');
    });
});

// ============================================
// ADMIN MASJID ROUTES
// ============================================
Route::middleware(['auth', 'active.user', 'admin.masjid', 'complete.profile'])->group(function () {
    // Konfigurasi Masjid (admin masjid bisa edit masjidnya sendiri)
    // Route::prefix('konfigurasi')->name('konfigurasi.')->group(function () {
    //     Route::get('/', [AdminMasjidController::class, 'index'])->name('index');
    //     Route::get('/edit', [AdminMasjidController::class, 'edit'])->name('edit');
    //     Route::put('/update', [AdminMasjidController::class, 'update'])->name('update');
    // });

        Route::prefix('konfigurasi-integrasi')->name('konfigurasi-integrasi.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin_masjid\KonfigurasiIntegrasiController::class, 'show'])->name('show');
        Route::get('/edit', [App\Http\Controllers\Admin_masjid\KonfigurasiIntegrasiController::class, 'edit'])->name('edit');
        Route::post('/update', [App\Http\Controllers\Admin_masjid\KonfigurasiIntegrasiController::class, 'update'])->name('update');
        
        // Testing endpoints
        Route::post('/test-whatsapp', [App\Http\Controllers\Admin_masjid\KonfigurasiIntegrasiController::class, 'testWhatsapp'])->name('test-whatsapp');
        Route::post('/test-midtrans', [App\Http\Controllers\Admin_masjid\KonfigurasiIntegrasiController::class, 'testMidtrans'])->name('test-midtrans');
        
        // Toggle status
        Route::post('/toggle-whatsapp', [App\Http\Controllers\Admin_masjid\KonfigurasiIntegrasiController::class, 'toggleWhatsappStatus'])->name('toggle-whatsapp');
        Route::post('/toggle-midtrans', [App\Http\Controllers\Admin_masjid\KonfigurasiIntegrasiController::class, 'toggleMidtransStatus'])->name('toggle-midtrans');
    });
    
    Route::prefix('program-zakat')->name('program-zakat.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin_masjid\ProgramZakatController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin_masjid\ProgramZakatController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin_masjid\ProgramZakatController::class, 'store'])->name('store');
        Route::get('/{uuid}', [App\Http\Controllers\Admin_masjid\ProgramZakatController::class, 'show'])->name('show');
        Route::get('/{uuid}/edit', [App\Http\Controllers\Admin_masjid\ProgramZakatController::class, 'edit'])->name('edit');
        Route::put('/{uuid}', [App\Http\Controllers\Admin_masjid\ProgramZakatController::class, 'update'])->name('update');
        Route::delete('/{uuid}', [App\Http\Controllers\Admin_masjid\ProgramZakatController::class, 'destroy'])->name('destroy');

        // Additional actions
        Route::post('/{uuid}/upload-foto', [App\Http\Controllers\Admin_masjid\ProgramZakatController::class, 'uploadFoto'])->name('upload-foto');
        Route::delete('/{uuid}/foto/{index}', [App\Http\Controllers\Admin_masjid\ProgramZakatController::class, 'deleteFoto'])->name('delete-foto');
        Route::patch('/{uuid}/change-status', [App\Http\Controllers\Admin_masjid\ProgramZakatController::class, 'changeStatus'])->name('change-status');
    });

    Route::prefix('rekening-masjid')->name('rekening-masjid.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin_masjid\RekeningMasjidController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin_masjid\RekeningMasjidController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin_masjid\RekeningMasjidController::class, 'store'])->name('store');
        Route::get('/{rekeningMasjid:uuid}', [\App\Http\Controllers\Admin_masjid\RekeningMasjidController::class, 'show'])->name('show');
        Route::get('/{rekeningMasjid:uuid}/edit', [\App\Http\Controllers\Admin_masjid\RekeningMasjidController::class, 'edit'])->name('edit');
        Route::put('/{rekeningMasjid:uuid}', [\App\Http\Controllers\Admin_masjid\RekeningMasjidController::class, 'update'])->name('update');
        Route::delete('/{rekeningMasjid:uuid}', [\App\Http\Controllers\Admin_masjid\RekeningMasjidController::class, 'destroy'])->name('destroy');

        // Additional actions
        Route::patch('/{rekeningMasjid:uuid}/toggle-active', [\App\Http\Controllers\Admin_masjid\RekeningMasjidController::class, 'toggleActive'])->name('toggle-active');
        Route::patch('/{rekeningMasjid:uuid}/set-primary', [\App\Http\Controllers\Admin_masjid\RekeningMasjidController::class, 'setPrimary'])->name('set-primary');
    });



    // Ubah bagian route amil menjadi:
    Route::prefix('amil')->name('amil.')->group(function () {
        Route::get('/', [AmilController::class, 'index'])->name('index');
        Route::get('/create', [AmilController::class, 'create'])->name('create');
        Route::post('/', [AmilController::class, 'store'])->name('store');
        Route::get('/{amil:uuid}', [AmilController::class, 'show'])->name('show');
        Route::get('/{amil:uuid}/edit', [AmilController::class, 'edit'])->name('edit');
        Route::put('/{amil:uuid}', [AmilController::class, 'update'])->name('update');
        Route::delete('/{amil:uuid}', [AmilController::class, 'destroy'])->name('destroy');
        Route::patch('/{amil:uuid}/toggle-status', [AmilController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/api/by-masjid/{masjidId}', [AmilController::class, 'getByMasjid'])->name('api.by-masjid');
    });

    // Laporan Keuangan Masjid
    Route::prefix('laporan-keuangan')->name('laporan-keuangan.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin_masjid\LaporanKeuanganController::class, 'index'])->name('index');
        Route::get('/{uuid}', [\App\Http\Controllers\Admin_masjid\LaporanKeuanganController::class, 'show'])->name('show');
        Route::post('/generate/{tahun}/{bulan}', [\App\Http\Controllers\Admin_masjid\LaporanKeuanganController::class, 'generate'])->name('generate');
        Route::post('/{uuid}/publish', [\App\Http\Controllers\Admin_masjid\LaporanKeuanganController::class, 'publish'])->name('publish');
        Route::get('/{uuid}/download-pdf', [\App\Http\Controllers\Admin_masjid\LaporanKeuanganController::class, 'downloadPDF'])->name('download.pdf');
        Route::get('/tahunan/{tahun}/download-pdf', [\App\Http\Controllers\Admin_masjid\LaporanKeuanganController::class, 'downloadTahunanPDF'])->name('download.tahunan.pdf');
        Route::get('/public/{uuid}/download-pdf', [\App\Http\Controllers\Admin_masjid\LaporanKeuanganController::class, 'downloadPublicPDF'])->name('public.download.pdf');

        // Public routes (transparansi)
        Route::prefix('public')->name('public.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin_masjid\LaporanKeuanganController::class, 'publicIndex'])->name('index');
            Route::get('/{uuid}', [\App\Http\Controllers\Admin_masjid\LaporanKeuanganController::class, 'publicShow'])->name('show');
        });


    });

    // NOTE: Tambahkan route untuk:
    // - Program Zakat
    // - Mustahik
    // - Rekening Masjid
    // - Laporan Keuangan Masjid
    // Sesuai dengan migration yang ada
});

// ============================================
// AMIL ROUTES
// ============================================
Route::middleware(['auth', 'active.user', 'amil', 'masjid.access'])->group(function () {
    // NOTE: Tambahkan route untuk amil:
    // - Input Transaksi Zakat
    // - Data Muzaki
    // - Data Mustahik (view only)
    // - Laporan Harian
// Transaksi Penerimaan
    Route::prefix('transaksi-penerimaan')->name('transaksi-penerimaan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'index'])
            ->name('index');
        Route::get('/create', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'create'])
            ->name('create');
        Route::post('/', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'store'])
            ->name('store');
        Route::get('/{uuid}', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'show'])
            ->name('show');
        Route::get('/{uuid}/print', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'printKwitansi'])
            ->name('print');

        Route::get('/{transaksi}/edit', [TransaksiPenerimaanController::class, 'edit'])->name('edit');
        Route::put('/{transaksi}', [TransaksiPenerimaanController::class, 'update'])->name('update');
        Route::delete('/{uuid}', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'destroy'])
            ->name('destroy');

        Route::get('/export/pdf', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'exportPdf'])
        ->name('export.pdf');
        Route::get('/export/excel', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'exportExcel'])
        ->name('export.excel');
        
        // API Routes untuk AJAX - PERBAIKI INI
        Route::get('/get-tipe-zakat', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'getTipeZakat'])
            ->name('get-tipe-zakat');
        Route::get('/get-nisab-info', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'getNisabInfo'])
            ->name('get-nisab-info');
        Route::post('/create-midtrans', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'createMidtransTransaction'])
            ->name('create-midtrans');
        
        // Status update routes
        Route::post('/{uuid}/verify', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'verify'])
            ->name('verify');
        Route::post('/{uuid}/konfirmasi-pembayaran', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'konfirmasiPembayaran'])
            ->name('konfirmasi-pembayaran');
        Route::post('/{uuid}/reject', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'reject'])
            ->name('reject');
        Route::post('/{uuid}/update-penjemputan', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'updatePenjemputan'])
            ->name('update-penjemputan');
    });

    Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/',         [ProfilAmilController::class, 'show'])           ->name('show');
    Route::get('/edit',     [ProfilAmilController::class, 'edit'])           ->name('edit');
    Route::put('/update',   [ProfilAmilController::class, 'update'])         ->name('update');
    Route::put('/password', [ProfilAmilController::class, 'updatePassword']) ->name('password');
});

});

Route::post('/midtrans/callback', [App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'midtransCallback'])
    ->name('midtrans.callback');

// ============================================
// MUSTAHIK ROUTES
// ============================================

// Mustahik - Admin Masjid & Amil (Both can create, edit, delete)
Route::middleware(['auth', 'active.user', 'role:admin_masjid,amil', 'masjid.access'])
    ->prefix('mustahik')->name('mustahik.')->group(function () {

        // CRUD - Both can access
        Route::get('/', [MustahikController::class, 'index'])->name('index');
        Route::get('/create', [MustahikController::class, 'create'])->name('create');
        Route::post('/', [MustahikController::class, 'store'])->name('store');
        Route::get('/{mustahik:uuid}', [MustahikController::class, 'show'])->name('show');
        Route::get('/{mustahik:uuid}/edit', [MustahikController::class, 'edit'])->name('edit');
        Route::put('/{mustahik:uuid}', [MustahikController::class, 'update'])->name('update');
        Route::delete('/{mustahik:uuid}', [MustahikController::class, 'destroy'])->name('destroy');

        // Wilayah API
        Route::get('/api/cities/{provinceCode}', [MustahikController::class, 'getCities'])->name('api.cities');
        Route::get('/api/districts/{cityCode}', [MustahikController::class, 'getDistricts'])->name('api.districts');
        Route::get('/api/villages/{districtCode}', [MustahikController::class, 'getVillages'])->name('api.villages');
    });

// Mustahik - Admin Masjid Only (Verifikasi)
Route::middleware(['auth', 'active.user', 'role:admin_masjid', 'masjid.access'])
    ->prefix('mustahik')->name('mustahik.')->group(function () {

        // Verifikasi (Only Admin Masjid)
        Route::patch('/{mustahik:uuid}/verify', [MustahikController::class, 'verify'])->name('verify');
        Route::patch('/{mustahik:uuid}/reject', [MustahikController::class, 'reject'])->name('reject');
        Route::patch('/{mustahik:uuid}/toggle-active', [MustahikController::class, 'toggleActive'])->name('toggle-active');
    });

// ============================================
// ROUTES UNTUK ADMIN MASJID DAN AMIL (BOTH)
// ============================================
Route::middleware(['auth', 'active.user', 'role:admin_masjid,amil', 'masjid.access'])->group(function () {
    // Route yang bisa diakses oleh admin masjid dan amil
    // Contoh: Laporan, Data Transaksi, dll
});

// ============================================
// API ROUTES (PUBLIC & PROTECTED)
// ============================================

// Public API (tanpa auth)
Route::prefix('api')->name('api.')->group(function () {
    // Wilayah Indonesia API (dapat diakses tanpa auth untuk keperluan registrasi)
    Route::prefix('wilayah')->name('wilayah.')->group(function () {
        Route::get('/provinces', [WilayahController::class, 'provinces'])->name('provinces');
        Route::get('/cities/{provinceCode}', [WilayahController::class, 'cities'])->name('cities');
        Route::get('/districts/{cityCode}', [WilayahController::class, 'districts'])->name('districts');
        Route::get('/villages/{districtCode}', [WilayahController::class, 'villages'])->name('villages');
        Route::get('/postal-code/{villageCode}', [WilayahController::class, 'postalCode'])->name('postal-code');
        Route::get('/search', [WilayahController::class, 'search'])->name('search');
    });

    // Auth Validation API (public)
    Route::get('/check-username', [AuthController::class, 'checkUsername'])->name('check-username');
    Route::get('/check-email', [AuthController::class, 'checkEmail'])->name('check-email');
});

// Protected API (dengan auth)
Route::middleware(['auth', 'active.user'])->prefix('api')->name('api.')->group(function () {
    // Wilayah API untuk admin (jika perlu)
    Route::prefix('wilayah')->name('wilayah.')->group(function () {
        Route::get('/admin/cities/{provinceCode}', [WilayahController::class, 'getCities'])
            ->name('admin.cities');
        Route::get('/admin/districts/{cityCode}', [WilayahController::class, 'getDistricts'])
            ->name('admin.districts');
        Route::get('/admin/villages/{districtCode}', [WilayahController::class, 'getVillages'])
            ->name('admin.villages');
        Route::get('/admin/postal-code/{villageCode}', [WilayahController::class, 'getPostalCode'])
            ->name('admin.postal-code');
    });
});