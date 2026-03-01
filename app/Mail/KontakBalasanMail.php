<?php

namespace App\Mail;

use App\Models\Kontak;
use App\Models\MailConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class KontakBalasanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $kontak;
    public $balasan;

    public function __construct(Kontak $kontak, string $balasan)
    {
        $this->kontak  = $kontak;
        $this->balasan = $balasan;

        // Load SMTP config dari database
        $mailConfig = MailConfig::first();
        if ($mailConfig && $mailConfig->isComplete()) {
            foreach ($mailConfig->toConfigArray() as $key => $value) {
                Config::set($key, $value);
            }
            Config::set('mail.default', $mailConfig->MAIL_MAILER ?? 'smtp');
        }
    }

    public function build()
    {
        return $this->subject('Re: ' . $this->kontak->subjek . ' - ' . config('app.name'))
                    ->view('emails.kontak-balasan');
    }
}