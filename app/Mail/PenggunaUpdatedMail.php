<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PenggunaUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string  $namaLengkap,
        public readonly string  $email,
        public readonly string  $username,
        public readonly string  $peran,
        public readonly ?string $namaLembaga  = null,
        public readonly bool    $passwordChanged = false,
        public readonly ?string $newPassword  = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->passwordChanged
                ? 'Password Akun Anda Telah Direset'
                : 'Data Akun Anda Telah Diperbarui',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pengguna-updated',
        );
    }
}