<?php

namespace App\Providers;

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
            if (Auth::check() && Auth::user()->peran === 'admin_masjid') {
                $masjid = Auth::user()->masjid;
                $pendingApprovalCount = $masjid
                    ? TransaksiPenyaluran::byMasjid($masjid->id)->byStatus('draft')->count()
                    : 0;
            } else {
                $pendingApprovalCount = 0;
            }

            // ── Badge count untuk menu sidebar amil ──
            $sidebarCounts = ['datang_langsung' => 0, 'daring' => 0, 'dijemput' => 0];

            if (Auth::check() && in_array(Auth::user()->peran, ['amil', 'admin_masjid'])) {
                $user     = Auth::user();
                $masjidId = $user->masjid_id;

                // Jika amil, coba ambil masjid_id dari relasi amil
                if ($user->peran === 'amil' && $user->amil) {
                    $masjidId = $user->amil->masjid_id ?? $masjidId;
                }

                if ($masjidId) {
                    $sidebarCounts = [
                        // Datang langsung: transaksi pending (belum verified)
                        'datang_langsung' => TransaksiPenerimaan::where('masjid_id', $masjidId)
                            ->where('metode_penerimaan', 'datang_langsung')
                            ->where('status', 'pending')
                            ->count(),

                        // Daring: menunggu konfirmasi pembayaran
                        'daring' => TransaksiPenerimaan::where('masjid_id', $masjidId)
                            ->where('metode_penerimaan', 'daring')
                            ->where('konfirmasi_status', 'menunggu_konfirmasi')
                            ->count(),

                        // Dijemput: menunggu penjemputan atau belum dilengkapi data zakat
                        'dijemput' => TransaksiPenerimaan::where('masjid_id', $masjidId)
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
            $view->with('sidebarCounts', $sidebarCounts);
        });
    }
}
