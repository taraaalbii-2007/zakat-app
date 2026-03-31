<?php

namespace App\Providers;

use App\Models\Bulletin;
use App\Models\KonfigurasiAplikasi;
use App\Models\TransaksiPenyaluran;
use App\Models\SetorKas;
use App\Models\Mustahik;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\TransaksiPenerimaan;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $appConfig = KonfigurasiAplikasi::getConfig();
        View::share('appConfig', $appConfig);

        View::composer('*', function ($view) {
            if (Auth::check() && Auth::user()->peran === 'admin_lembaga') {
                $lembaga = Auth::user()->lembaga;

                $pendingApprovalCount = $lembaga
                    ? TransaksiPenyaluran::byLembaga($lembaga->id)->byStatus('draft')->count()
                    : 0;
            } else {
                $pendingApprovalCount = 0;
            }

            // Count setor kas pending untuk admin lembaga
            if (Auth::check() && Auth::user()->peran === 'admin_lembaga') {
                $lembaga = Auth::user()->lembaga;
                $pendingSetorKasCount = $lembaga
                    ? SetorKas::byLembaga($lembaga->id)->pending()->count()
                    : 0;
            } else {
                $pendingSetorKasCount = 0;
            }

            // Count mustahik pending untuk admin lembaga
if (Auth::check() && Auth::user()->peran === 'admin_lembaga') {
    $lembaga = Auth::user()->lembaga;
    $pendingMustahikCount = $lembaga
        ? Mustahik::where('lembaga_id', $lembaga->id)
            ->where('status_verifikasi', 'pending')
            ->count()
        : 0;
} else {
    $pendingMustahikCount = 0;
}

            
            // Count bulletin pending untuk superadmin (semua bulletin dari lembaga yang menunggu approval)
            if (Auth::check() && Auth::user()->peran === 'superadmin') {
                $bulletinPendingCount = Bulletin::whereNotNull('lembaga_id')
                    ->where('status', 'pending')
                    ->count();
            } else {
                $bulletinPendingCount = 0;
            }

            // ── Badge count untuk menu sidebar amil ──
            $sidebarCounts = ['datang_langsung' => 0, 'daring' => 0, 'dijemput' => 0];

            if (Auth::check() && in_array(Auth::user()->peran, ['amil', 'admin_lembaga'])) {
                $user      = Auth::user();
                $lembagaId = $user->lembaga_id;

                // Jika amil, coba ambil lembaga_id dari relasi amil
                if ($user->peran === 'amil' && $user->amil) {
                    $lembagaId = $user->amil->lembaga_id ?? $lembagaId;
                }

                if ($lembagaId) {
                    // Tentukan apakah user ini amil (bukan admin_lembaga)
                    $isAmil  = $user->peran === 'amil';
                    $amilId  = ($isAmil && $user->amil) ? $user->amil->id : null;

                    $sidebarCounts = [
                        'datang_langsung' => 0,

                        // Daring: semua amil se-lembaga bisa konfirmasi, JANGAN filter by amil_id
                        'daring' => TransaksiPenerimaan::where('lembaga_id', $lembagaId)
                            ->where('metode_penerimaan', 'daring')
                            ->where('status', 'pending')
                            ->where('konfirmasi_status', 'menunggu_konfirmasi')
                            ->count(),

                        // Dijemput: hanya amil yang dipilih muzakki, atau jika tidak ada pilihan (amil_id null)
                        'dijemput' => TransaksiPenerimaan::where('lembaga_id', $lembagaId)
                            ->where('metode_penerimaan', 'dijemput')
                            ->where('status', 'pending')
                            ->where('status_penjemputan', 'menunggu')
                            ->when($isAmil && $amilId, function ($q) use ($amilId) {
                                $q->where(function ($sub) use ($amilId) {
                                    $sub->where('amil_id', $amilId)
                                        ->orWhereNull('amil_id');
                                });
                            })
                            ->count(),
                    ];
                }
            }

            $view->with('pendingApprovalCount', $pendingApprovalCount);
            $view->with('bulletinPendingCount', $bulletinPendingCount);
            $view->with('sidebarCounts', $sidebarCounts);
            $view->with('pendingSetorKasCount', $pendingSetorKasCount);
            $view->with('pendingMustahikCount', $pendingMustahikCount);
        });
    }
}