<?php

namespace App\Mail;

use App\Models\Amil;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AmilRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $amil;
    public $username;
    public $password;

    public function __construct(Amil $amil, $username = null, $password = null)
    {
        $this->amil = $amil;
        $this->username = $username;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Registrasi Amil Berhasil - Niat Zakat')
                    ->view('emails.amil-registration');
    }
}