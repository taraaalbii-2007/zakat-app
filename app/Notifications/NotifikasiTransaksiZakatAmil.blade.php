<?php

namespace App\Notifications;

use App\Models\TransaksiPenerimaan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

/**
 * NotifikasiTransaksiZakatAmil
 * 
 * Notifikasi yang dikirim ke amil ketika ada transaksi zakat baru dari muzakki
 * Bisa untuk metode DARING atau DIJEMPUT
 */
class NotifikasiTransaksiZakatAmil extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transaksi;

    public function __construct(TransaksiPenerimaan $transaksi)
    {
        $this->transaksi = $transaksi;
        $this->queue = 'notifications';
    }

    /**
     * Gunakan database channel
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Format notifikasi untuk database
     */
    public function toDatabase($notifiable)
    {
        $metode = $this->transaksi->metode_penerimaan === 'daring' 
            ? 'Pembayaran Daring' 
            : 'Permintaan Penjemputan';

        $jumlahFormatted = 'Rp ' . number_format($this->transaksi->jumlah, 0, ',', '.');

        return new DatabaseMessage([
            'title' => 'Transaksi Zakat Baru - ' . $metode,
            'message' => $this->transaksi->muzakki_nama . ' mengirimkan transaksi zakat ' . $jumlahFormatted,
            'icon' => 'money',
            'color' => $this->transaksi->metode_penerimaan === 'daring' ? 'indigo' : 'orange',
            'action_url' => url('/transaksi-daring/') . '/' . $this->transaksi->uuid,
            'transaksi_id' => $this->transaksi->id,
            'transaksi_uuid' => $this->transaksi->uuid,
            'no_transaksi' => $this->transaksi->no_transaksi,
            'muzakki_nama' => $this->transaksi->muzakki_nama,
            'muzakki_telepon' => $this->transaksi->muzakki_telepon,
            'jumlah' => $this->transaksi->jumlah,
            'metode' => $this->transaksi->metode_penerimaan,
            'status' => $this->transaksi->status,
        ]);
    }
}