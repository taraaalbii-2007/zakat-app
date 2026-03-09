<?php

namespace App\Mail;

use App\Models\TransaksiPenerimaan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KwitansiTransaksiMail extends Mailable
{
    use Queueable, SerializesModels;

    public TransaksiPenerimaan $transaksi;
    public string $downloadUrl;

    public function __construct(TransaksiPenerimaan $transaksi, string $downloadUrl)
    {
        $this->transaksi   = $transaksi;
        $this->downloadUrl = $downloadUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kwitansi Transaksi Zakat - ' . $this->transaksi->no_transaksi,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.kwitansi-transaksi',
        );
    }
}