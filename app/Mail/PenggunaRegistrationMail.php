<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PenggunaRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $namaLengkap;
    public string $email;
    public string $username;
    public string $password;
    public string $peran;
    public string $peranLabel;
    public ?string $namaLembaga;
    public string $loginUrl;

    public function __construct(
        string  $namaLengkap,
        string  $email,
        string  $username,
        string  $password,
        string  $peran,
        ?string $namaLembaga = null
    ) {
        $this->namaLengkap = $namaLengkap;
        $this->email       = $email;
        $this->username    = $username;
        $this->password    = $password;
        $this->peran       = $peran;
        $this->namaLembaga  = $namaLembaga;
        $this->loginUrl    = url('/login');

        $this->peranLabel = match ($peran) {
            'admin_lembaga' => 'Admin Lembaga',
            'amil'         => 'Amil',
            'superadmin'   => 'Super Admin',
            'muzakki'      => 'Muzakki',
            default        => ucfirst($peran),
        };
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Akun ' . $this->peranLabel . ' Anda Telah Dibuat – ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pengguna-registration',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}