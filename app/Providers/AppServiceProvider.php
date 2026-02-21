<?php

namespace App\Providers;

use App\Models\KonfigurasiAplikasi;
use App\Models\TransaksiPenyaluran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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

        // Badge count penyaluran menunggu approval (khusus admin_masjid)
        View::composer('*', function ($view) {
            if (Auth::check() && Auth::user()->peran === 'admin_masjid') {
                $masjid = Auth::user()->masjid;
                $pendingApprovalCount = $masjid
                    ? TransaksiPenyaluran::byMasjid($masjid->id)->byStatus('draft')->count()
                    : 0;
            } else {
                $pendingApprovalCount = 0;
            }

            $view->with('pendingApprovalCount', $pendingApprovalCount);
        });
    }
}