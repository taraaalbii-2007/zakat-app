<?php

namespace App\Providers;

use App\Models\Bulletin;
use App\Models\KonfigurasiAplikasi;
use App\Models\TransaksiPenyaluran;
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
                    $sidebarCounts = [
                        // Datang langsung: transaksi pending (belum verified)
                        'datang_langsung' => 0,

                        // Daring: menunggu konfirmasi pembayaran
                        'daring' => TransaksiPenerimaan::where('lembaga_id', $lembagaId)
                            ->where('metode_penerimaan', 'daring')
                            ->where('konfirmasi_status', 'menunggu_konfirmasi')
                            ->count(),

                        // Dijemput: menunggu penjemputan atau belum dilengkapi data zakat
                        'dijemput' => TransaksiPenerimaan::where('lembaga_id', $lembagaId)
                            ->where('metode_penerimaan', 'dijemput')
                            ->where(function ($q) {
                                $q->where('status_penjemputan', 'menunggu')
                                    ->orWhereNull('jenis_zakat_id');
                            })
                            ->count(),
                    ];
                }
            }

            $view->with('pendingApprovalCount', $pendingApprovalCount);
            $view->with('bulletinPendingCount', $bulletinPendingCount);
            $view->with('sidebarCounts', $sidebarCounts);
        });
    }
}