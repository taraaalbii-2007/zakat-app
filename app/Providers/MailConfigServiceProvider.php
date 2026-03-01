<?php

namespace App\Providers;

use App\Models\MailConfig;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class MailConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        try {
            $mailConfig = MailConfig::first();

            if ($mailConfig && $mailConfig->isComplete()) {
                Config::set('mail.default', $mailConfig->MAIL_MAILER ?? 'smtp');
                Config::set('mail.mailers.smtp.host', $mailConfig->MAIL_HOST);
                Config::set('mail.mailers.smtp.port', (int) ($mailConfig->MAIL_PORT ?? 2525));
                Config::set('mail.mailers.smtp.username', $mailConfig->MAIL_USERNAME);
                Config::set('mail.mailers.smtp.password', $mailConfig->MAIL_PASSWORD);
                Config::set('mail.mailers.smtp.encryption', $mailConfig->MAIL_ENCRYPTION ?? 'tls');

                // Tambah timeout agar tidak hang 60 detik
                Config::set('mail.mailers.smtp.timeout', 10);

                Config::set('mail.from.address', $mailConfig->MAIL_FROM_ADDRESS);
                Config::set('mail.from.name', $mailConfig->MAIL_FROM_NAME ?? config('app.name'));

                Log::info('Mail configuration loaded from database', [
                    'host'       => $mailConfig->MAIL_HOST,
                    'port'       => $mailConfig->MAIL_PORT,
                    'encryption' => $mailConfig->MAIL_ENCRYPTION,
                    'from'       => $mailConfig->MAIL_FROM_ADDRESS,
                ]);
            } else {
                Log::warning('Mail configuration not found or incomplete in database');
            }
        } catch (\Exception $e) {
            Log::error('Failed to load mail configuration from database: ' . $e->getMessage());
        }
    }
}