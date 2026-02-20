<?php

namespace App\Mail;

use App\Models\Pengguna;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DataAkunDiubahNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $perubahan;
    public $config;

    /**
     * Create a new message instance.
     */
    public function __construct(Pengguna $user, string $perubahan)
    {
        $this->user = $user;
        $this->perubahan = $perubahan; // 'email' atau 'password'
        $this->config = \App\Models\KonfigurasiAplikasi::first();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjek = $this->perubahan === 'email' 
            ? 'Email Akun Telah Diubah - Verifikasi Login Ulang'
            : 'Password Akun Telah Diubah - Verifikasi Login Ulang';
            
        return new Envelope(
            subject: $subjek,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.data-akun-diubah',
        );
    }
}