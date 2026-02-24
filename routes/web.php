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
use App\Http\Controllers\Amil\SetorKasController;
use App\Http\Controllers\Superadmin\SuperadminAmilController;
use App\Http\Controllers\Superadmin\SuperadminMustahikController;
use App\Http\Controllers\Superadmin\SuperadminTransaksiPenerimaanController;
use App\Http\Controllers\Superadmin\SuperadminTransaksiPenyaluranController;
use App\Http\Controllers\Superadmin\ProfilSuperadminController;
use App\Http\Controllers\Admin_masjid\ProfilAdminMasjidController;
use App\Http\Controllers\LandingController;


Route::get('/', [LandingController::class, 'index'])->name('landing');

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

    Route::get('/complete-profile-muzakki/{token}', [AuthController::class, 'showCompleteProfileMuzakki'])
        ->name('complete-profile-muzakki');
    Route::post('/complete-profile-muzakki/{token}', [AuthController::class, 'storeCompleteProfileMuzakki'])
        ->name('complete-profile-muzakki.store');

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
    Route::prefix('superadmin-amil')->name('superadmin.amil.')->group(function () {
        Route::get('/', [SuperadminAmilController::class, 'index'])->name('index');
    });

    Route::prefix('superadmin-mustahik')->name('superadmin.mustahik.')->group(function () {
        Route::get('/', [SuperadminMustahikController::class, 'index'])->name('index');
    });

    Route::prefix('superadmin-transaksi-penerimaan')->name('superadmin.transaksi-penerimaan.')->group(function () {
        Route::get('/', [SuperadminTransaksiPenerimaanController::class, 'index'])->name('index');
    });

    Route::prefix('superadmin-transaksi-penyaluran')->name('superadmin.transaksi-penyaluran.')->group(function () {
        Route::get('/', [SuperadminTransaksiPenyaluranController::class, 'index'])->name('index');
    });

    Route::prefix('pengguna')->name('pengguna.')->group(function () {
        Route::get('/',                     [\App\Http\Controllers\Superadmin\PenggunaController::class, 'index'])->name('index');
        Route::get('/create',               [\App\Http\Controllers\Superadmin\PenggunaController::class, 'create'])->name('create');
        Route::post('/',                    [\App\Http\Controllers\Superadmin\PenggunaController::class, 'store'])->name('store');
        Route::get('/{uuid}',               [\App\Http\Controllers\Superadmin\PenggunaController::class, 'show'])->name('show');
        Route::get('/{uuid}/edit',          [\App\Http\Controllers\Superadmin\PenggunaController::class, 'edit'])->name('edit');
        Route::put('/{uuid}',               [\App\Http\Controllers\Superadmin\PenggunaController::class, 'update'])->name('update');
        Route::delete('/{uuid}',            [\App\Http\Controllers\Superadmin\PenggunaController::class, 'destroy'])->name('destroy');
        Route::patch('/{uuid}/toggle-status', [\App\Http\Controllers\Superadmin\PenggunaController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{uuid}/reset-password', [\App\Http\Controllers\Superadmin\PenggunaController::class, 'resetPassword'])->name('reset-password');
    });

    Route::prefix('muzaki')->name('muzaki.')->group(function () {
        Route::get('/',       [App\Http\Controllers\Superadmin\MuzakiController::class, 'index'])->name('index');
        Route::get('/detail', [App\Http\Controllers\Superadmin\MuzakiController::class, 'show'])->name('show');
        // Route::get('/export', [App\Http\Controllers\Superadmin\MuzakiController::class, 'export'])->name('export'); // opsional
    });
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

    Route::prefix('superadmin-profil')->name('superadmin.profil.')->group(function () {
        Route::get('/',                     [ProfilSuperadminController::class, 'show'])->name('show');
        Route::get('/edit',                 [ProfilSuperadminController::class, 'edit'])->name('edit');
        Route::put('/update',                [ProfilSuperadminController::class, 'update'])->name('update');

        // Routes khusus untuk password
        Route::get('/ubah-password',         [ProfilSuperadminController::class, 'editPassword'])->name('password.edit');
        Route::put('/ubah-password',          [ProfilSuperadminController::class, 'updatePassword'])->name('password.update');
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
    Route::prefix('admin-masjid-muzaki')->name('admin-masjid.muzaki.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin_masjid\AdminMasjidMuzakiController::class, 'index'])->name('index');
        Route::get('/amil/{amilId}/muzaki', [App\Http\Controllers\Admin_masjid\AdminMasjidMuzakiController::class, 'getMuzakiByAmil'])->name('amil.muzaki');
    });

    Route::prefix('konfigurasi-integrasi')->name('konfigurasi-integrasi.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin_masjid\KonfigurasiIntegrasiController::class, 'show'])->name('show');
        Route::get('/edit', [App\Http\Controllers\Admin_masjid\KonfigurasiIntegrasiController::class, 'edit'])->name('edit');
        Route::post('/update', [App\Http\Controllers\Admin_masjid\KonfigurasiIntegrasiController::class, 'update'])->name('update');

        // Testing endpoints
        Route::post('/test-whatsapp', [App\Http\Controllers\Admin_masjid\KonfigurasiIntegrasiController::class, 'testWhatsapp'])->name('test-whatsapp');

        // Toggle status
        Route::post('/toggle-whatsapp', [App\Http\Controllers\Admin_masjid\KonfigurasiIntegrasiController::class, 'toggleWhatsappStatus'])->name('toggle-whatsapp');
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

    Route::prefix('admin-setor-kas')->name('admin-masjid.setor-kas.')->group(function () {
        Route::get('/pending', [App\Http\Controllers\Admin_masjid\TerimaSetorKasController::class, 'pending'])->name('pending');
        Route::get('/riwayat', [App\Http\Controllers\Admin_masjid\TerimaSetorKasController::class, 'riwayat'])->name('riwayat');
        Route::get('/{setorKas}', [App\Http\Controllers\Admin_masjid\TerimaSetorKasController::class, 'show'])->name('show')->where('setorKas', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
        Route::post('/{setorKas}/proses', [App\Http\Controllers\Admin_masjid\TerimaSetorKasController::class, 'proses'])->name('proses')->where('setorKas', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
    });

    Route::prefix('admin-masjid-profil')->name('admin-masjid.profil.')->group(function () {
        Route::get('/',             [ProfilAdminMasjidController::class, 'show'])->name('show');
        Route::get('/edit',         [ProfilAdminMasjidController::class, 'edit'])->name('edit');
        Route::put('/update',       [ProfilAdminMasjidController::class, 'update'])->name('update');

        // Ubah Email (halaman tersendiri)
        Route::get('/ubah-email',   [ProfilAdminMasjidController::class, 'editEmail'])->name('email.edit');
        Route::put('/ubah-email',   [ProfilAdminMasjidController::class, 'updateEmail'])->name('email.update');

        // Ubah Password
        Route::get('/ubah-password', [ProfilAdminMasjidController::class, 'editPassword'])->name('password.edit');
        Route::put('/ubah-password', [ProfilAdminMasjidController::class, 'updatePassword'])->name('password.update');
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
    Route::prefix('pemantauan-transaksi')->name('pemantauan-transaksi.')->group(function () {
        // INDEX — semua transaksi (tanpa tombol create/aksi)
        Route::get('/', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'index'])
            ->name('index');

        // EXPORT (TETAP ADA)
        Route::get('/export/pdf',   [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'exportPdf'])
            ->name('export.pdf');
        Route::get('/export/excel', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'exportExcel'])
            ->name('export.excel');

        // SHOW — detail transaksi
        Route::get('/{uuid}', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'showPemantauan'])
            ->name('show')->whereUuid('uuid');
    });


    Route::prefix('transaksi-datang-langsung')->name('transaksi-datang-langsung.')->group(function () {
        // INDEX DATANG LANGSUNG
        Route::get('/', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'indexDatangLangsung'])
            ->name('index');

        // EXPORT
        Route::get('/export/excel', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'exportExcel'])
            ->name('export.excel');

        // CREATE
        Route::get('/create', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'createDatangLangsung'])
            ->name('create');

        // STORE
        Route::post('/', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'storeDatangLangsung'])

            ->name('store');

        // SHOW
        Route::get('/{uuid}', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'showDatangLangsung'])
            ->name('show')->whereUuid('uuid');

        // EDIT & UPDATE
        Route::get('/{uuid}/edit', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'edit'])
            ->name('edit')->whereUuid('uuid');
        Route::put('/{uuid}', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'update'])
            ->name('update')->whereUuid('uuid');

        // DELETE
        Route::delete('/{uuid}', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'destroy'])
            ->name('destroy')->whereUuid('uuid');

        // PRINT KWITANSI
        Route::get('/{uuid}/print', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'printKwitansi'])
            ->name('print')->whereUuid('uuid');

        // STATUS ACTIONS
        Route::post('/{uuid}/verify', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'verify'])
            ->name('verify')
            ->whereUuid('uuid')
            ->middleware(['auth', 'active.user', 'amil', 'masjid.access']);
        Route::post('/{uuid}/reject', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'reject'])
            ->name('reject')->whereUuid('uuid');
    });

    Route::prefix('transaksi-dijemput')->name('transaksi-dijemput.')->group(function () {
        // INDEX
        Route::get('/', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'indexDijemput'])
            ->name('index');

        // CREATE
        Route::get('/create', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'createDijemput'])
            ->name('create');

        // STORE
        Route::post('/', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'storeDijemput'])
            ->name('store');

        // SHOW
        Route::get('/{uuid}', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'showDijemput'])
            ->name('show')->whereUuid('uuid');

        // EDIT & UPDATE
        Route::get('/{uuid}/edit', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'edit'])
            ->name('edit')->whereUuid('uuid');
        Route::put('/{uuid}', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'update'])
            ->name('update')->whereUuid('uuid');

        // DELETE
        Route::delete('/{uuid}', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'destroy'])
            ->name('destroy')->whereUuid('uuid');

        // STATUS PENJEMPUTAN (AJAX)
        Route::post(
            '/{uuid}/update-status-penjemputan',
            [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'updateStatusPenjemputan']
        )
            ->name('update-status-penjemputan')->whereUuid('uuid');
        Route::post(
            '/{uuid}/konfirmasi-pembayaran',
            [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'konfirmasiPembayaran']
        )
            ->name('konfirmasi-pembayaran')->whereUuid('uuid');
        Route::post(
            '/{uuid}/tolak-pembayaran',
            [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'tolakPembayaran']
        )
            ->name('tolak-pembayaran')->whereUuid('uuid');
    });

    Route::prefix('transaksi-daring')->name('transaksi-daring.')->group(function () {
        // INDEX DARING
        Route::get('/', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'indexDaring'])
            ->name('index');

        // SHOW
        Route::get('/{uuid}', [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'showDaring'])
            ->name('show')->whereUuid('uuid');

        // KONFIRMASI PEMBAYARAN DARING
        Route::post(
            '/{uuid}/konfirmasi-pembayaran',
            [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'konfirmasiPembayaran']
        )
            ->name('konfirmasi-pembayaran')->whereUuid('uuid');
        Route::post(
            '/{uuid}/tolak-pembayaran',
            [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'tolakPembayaran']
        )
            ->name('tolak-pembayaran')->whereUuid('uuid');
    });

    Route::prefix('transaksi-api')->name('transaksi-api.')->group(function () {
        Route::get('/get-tipe-zakat',      [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'getTipeZakat'])
            ->name('get-tipe-zakat');
        Route::get('/get-nisab-info',      [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'getNisabInfo'])
            ->name('get-nisab-info');
        Route::post('/hitung-info-bayar',  [\App\Http\Controllers\Amil\TransaksiPenerimaanController::class, 'hitungInfoPembayaran'])
            ->name('hitung-info-bayar');
    });


    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/',         [ProfilAmilController::class, 'show'])->name('show');
        Route::get('/edit',     [ProfilAmilController::class, 'edit'])->name('edit');
        Route::put('/update',   [ProfilAmilController::class, 'update'])->name('update');
        Route::put('/password', [ProfilAmilController::class, 'updatePassword'])->name('password');
    });

    Route::prefix('kas-harian')->name('kas-harian.')->group(function () {
        // Index — lihat kas hari ini (bisa dengan ?tanggal=YYYY-MM-DD)
        Route::get('/', [App\Http\Controllers\Amil\KasHarianAmilController::class, 'index'])
            ->name('index');

        // Buka kas baru hari ini
        Route::post('/buka', [App\Http\Controllers\Amil\KasHarianAmilController::class, 'bukaKas'])
            ->name('buka');

        // Tutup kas hari ini
        Route::post('/tutup', [App\Http\Controllers\Amil\KasHarianAmilController::class, 'tutupKas'])
            ->name('tutup');

        // Buka kembali kas (reopen — hanya kas hari ini)
        Route::post('/{uuid}/buka-kembali', [App\Http\Controllers\Amil\KasHarianAmilController::class, 'bukaKembali'])
            ->name('buka-kembali');

        // Simpan catatan saja (tanpa tutup kas)
        Route::post('/simpan-catatan', [App\Http\Controllers\Amil\KasHarianAmilController::class, 'simpanCatatan'])
            ->name('simpan-catatan');

        // Riwayat kas harian (history page)
        Route::get('/history', [App\Http\Controllers\Amil\KasHarianAmilController::class, 'history'])
            ->name('history');

        // Export Excel history
        Route::get('/export-excel', [App\Http\Controllers\Amil\KasHarianAmilController::class, 'exportExcel'])
            ->name('export-excel');
    });

    Route::prefix('setor-kas')->name('amil.setor-kas.')->group(function () {
        Route::get('/', [SetorKasController::class, 'index'])->name('index');
        Route::get('/create', [SetorKasController::class, 'create'])->name('create');
        Route::post('/', [SetorKasController::class, 'store'])->name('store');
        Route::post('/api/hitung-rekap', [SetorKasController::class, 'hitungRekapApi'])->name('api.hitung-rekap');

        // Tambahkan ->whereUuid() di semua wildcard
        Route::get('/{setorKas}', [SetorKasController::class, 'show'])->name('show')->whereUuid('setorKas');
        Route::get('/{setorKas}/edit', [SetorKasController::class, 'edit'])->name('edit')->whereUuid('setorKas');
        Route::put('/{setorKas}', [SetorKasController::class, 'update'])->name('update')->whereUuid('setorKas');
        Route::delete('/{setorKas}', [SetorKasController::class, 'destroy'])->name('destroy')->whereUuid('setorKas');
    });

    Route::prefix('kunjungan')->name('amil.kunjungan.')->group(function () {

        // Kalender (index)
        Route::get('/', [\App\Http\Controllers\Amil\KunjunganMustahikController::class, 'index'])
            ->name('index');

        // API Events untuk FullCalendar
        Route::get('/events', [\App\Http\Controllers\Amil\KunjunganMustahikController::class, 'events'])
            ->name('events');

        // API List view toggle
        Route::get('/list-data', [\App\Http\Controllers\Amil\KunjunganMustahikController::class, 'listData'])
            ->name('list-data');

        // API Autocomplete mustahik
        Route::get('/search-mustahik', [\App\Http\Controllers\Amil\KunjunganMustahikController::class, 'searchMustahik'])
            ->name('search-mustahik');

        // CRUD
        Route::get('/create', [\App\Http\Controllers\Amil\KunjunganMustahikController::class, 'create'])
            ->name('create');

        Route::post('/', [\App\Http\Controllers\Amil\KunjunganMustahikController::class, 'store'])
            ->name('store');

        Route::get('/{uuid}', [\App\Http\Controllers\Amil\KunjunganMustahikController::class, 'show'])
            ->name('show')
            ->whereUuid('uuid');

        Route::get('/{uuid}/edit', [\App\Http\Controllers\Amil\KunjunganMustahikController::class, 'edit'])
            ->name('edit')
            ->whereUuid('uuid');

        Route::put('/{uuid}', [\App\Http\Controllers\Amil\KunjunganMustahikController::class, 'update'])
            ->name('update')
            ->whereUuid('uuid');

        Route::delete('/{uuid}', [\App\Http\Controllers\Amil\KunjunganMustahikController::class, 'destroy'])
            ->name('destroy')
            ->whereUuid('uuid');

        // Actions
        Route::post('/{uuid}/cancel', [\App\Http\Controllers\Amil\KunjunganMustahikController::class, 'cancel'])
            ->name('cancel')
            ->whereUuid('uuid');

        Route::get('/{uuid}/finish', [\App\Http\Controllers\Amil\KunjunganMustahikController::class, 'finish'])
            ->name('finish')
            ->whereUuid('uuid');

        Route::post('/{uuid}/complete', [\App\Http\Controllers\Amil\KunjunganMustahikController::class, 'complete'])
            ->name('complete')
            ->whereUuid('uuid');

        Route::delete('/{uuid}/foto', [\App\Http\Controllers\Amil\KunjunganMustahikController::class, 'hapusFoto'])
            ->name('hapus-foto')
            ->whereUuid('uuid');
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
// SESUDAH — tambahkan ke blok ini yang sudah ada
Route::middleware(['auth', 'active.user', 'role:admin_masjid,amil', 'masjid.access'])->group(function () {

    Route::prefix('transaksi-penyaluran')->name('transaksi-penyaluran.')->group(function () {
        Route::get('/', [App\Http\Controllers\Amil\TransaksiPenyaluranController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Amil\TransaksiPenyaluranController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Amil\TransaksiPenyaluranController::class, 'store'])->name('store');
        Route::get('/export/pdf',   [App\Http\Controllers\Amil\TransaksiPenyaluranController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [App\Http\Controllers\Amil\TransaksiPenyaluranController::class, 'exportExcel'])->name('export.excel');
        Route::get('/{transaksiPenyaluran:uuid}', [App\Http\Controllers\Amil\TransaksiPenyaluranController::class, 'show'])->name('show');
        Route::get('/{transaksiPenyaluran:uuid}/edit', [App\Http\Controllers\Amil\TransaksiPenyaluranController::class, 'edit'])->name('edit');
        Route::put('/{transaksiPenyaluran:uuid}', [App\Http\Controllers\Amil\TransaksiPenyaluranController::class, 'update'])->name('update');
        Route::delete('/{transaksiPenyaluran:uuid}', [App\Http\Controllers\Amil\TransaksiPenyaluranController::class, 'destroy'])->name('destroy');
        Route::post('/{transaksiPenyaluran:uuid}/konfirmasi-disalurkan', [App\Http\Controllers\Amil\TransaksiPenyaluranController::class, 'konfirmasiDisalurkan'])->name('konfirmasi-disalurkan');
        Route::delete('/dokumentasi/{dokumentasi}', [App\Http\Controllers\Amil\TransaksiPenyaluranController::class, 'hapusDokumentasi'])->name('dokumentasi.destroy');
        Route::get('/{transaksiPenyaluran:uuid}/cetak', [App\Http\Controllers\Amil\TransaksiPenyaluranController::class, 'cetak'])->name('cetak');
    });


    // approve/reject tetap di sini juga (pindahkan dari blok admin_masjid)
    Route::prefix('transaksi-penyaluran')->name('transaksi-penyaluran.')->group(function () {
        Route::post('/{transaksiPenyaluran}/approve', [App\Http\Controllers\Admin_masjid\ApprovalPenyaluranController::class, 'approve'])->name('approve');
        Route::post('/{transaksiPenyaluran}/reject', [App\Http\Controllers\Admin_masjid\ApprovalPenyaluranController::class, 'reject'])->name('reject');
    });
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

Route::middleware(['auth', 'active.user', 'muzakki', 'masjid.access'])->group(function () {
    Route::prefix('transaksi-daring-muzakki')->name('transaksi-daring-muzakki.')->group(function () {
        
        // GANTI dari AmilController ke MuzakkiController
        Route::get('/', [\App\Http\Controllers\Muzakki\TransaksiZakatController::class, 'index'])
            ->name('index');
        
        // GANTI dari KunjunganMustahikController ke TransaksiZakatController
        Route::get('/create', [\App\Http\Controllers\Muzakki\TransaksiZakatController::class, 'create'])
            ->name('create');

        Route::post('/', [\App\Http\Controllers\Muzakki\TransaksiZakatController::class, 'store'])
            ->name('store');

        Route::get('/{uuid}', [\App\Http\Controllers\Muzakki\TransaksiZakatController::class, 'show'])
            ->name('show');
    });
});

Route::middleware(['auth', 'active.user'])->group(function () {
    // Mark notifikasi sebagai sudah dibaca (update session)
    Route::post('/notif/mark-read', [\App\Http\Controllers\NotifController::class, 'markRead'])
        ->name('notif.mark-read');
});