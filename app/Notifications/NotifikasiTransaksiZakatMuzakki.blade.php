<?php

namespace App\Notifications;

use App\Models\TransaksiPenerimaan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

/**
 * NotifikasiTransaksiZakatMuzakki
 * 
 * Notifikasi yang dikirim ke muzakki setelah transaksi berhasil disimpan
 * Konfirmasi bahwa transaksi sudah diterima dan menunggu verifikasi amil
 */
class NotifikasiTransaksiZakatMuzakki extends Notification implements ShouldQueue
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
            ? 'Daring (Transfer/QRIS)' 
            : 'Dijemput';

        $jumlahFormatted = 'Rp ' . number_format($this->transaksi->jumlah, 0, ',', '.');

        $pesan = $this->transaksi->metode_penerimaan === 'daring'
            ? 'Transaksi zakat daring Anda telah diterima. Menunggu verifikasi dari amil.'
            : 'Permintaan penjemputan zakat Anda telah diterima. Amil akan segera menghubungi Anda.';

        return new DatabaseMessage([
            'title' => 'Transaksi Zakat Berhasil - ' . $metode,
            'message' => $pesan,
            'icon' => 'check-circle',
            'color' => 'green',
            'action_url' => url('/transaksi-daring-muzakki/') . '/' . $this->transaksi->uuid,
            'transaksi_id' => $this->transaksi->id,
            'transaksi_uuid' => $this->transaksi->uuid,
            'no_transaksi' => $this->transaksi->no_transaksi,
            'jumlah' => $this->transaksi->jumlah,
            'metode' => $this->transaksi->metode_penerimaan,
            'status' => $this->transaksi->status,
            'tips' => $this->transaksi->metode_penerimaan === 'dijemput'
                ? 'Pastikan nomor telepon Anda aktif agar amil bisa menghubungi.'
                : 'Simpan bukti transfer Anda untuk referensi.',
        ]);
    }
}