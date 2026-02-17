<?php

namespace App\Services;

use App\Models\KonfigurasiMidtrans;
use App\Models\TransaksiPenerimaan;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected $config;
    protected $masjidId;

    public function __construct($masjidId)
    {
        $this->masjidId = $masjidId;
        $this->config = KonfigurasiMidtrans::where('masjid_id', $masjidId)
            ->where('is_active', true)
            ->first();

        if (!$this->config) {
            throw new \Exception('Konfigurasi Midtrans tidak ditemukan atau tidak aktif');
        }

        $this->initMidtransConfig();
    }

    protected function initMidtransConfig()
    {
        // Set Midtrans configuration
        Config::$serverKey = $this->config->server_key;
        Config::$clientKey = $this->config->client_key;
        Config::$isProduction = $this->config->environment === 'production';
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Create Midtrans transaction and get Snap token
     */
    public function createTransaction(TransaksiPenerimaan $transaksi)
    {
        try {
            $params = $this->buildTransactionParams($transaksi);
            
            // Get Snap Token
            $snapToken = Snap::getSnapToken($params);
            
            // Get Payment URL
            $snapResponse = Snap::createTransaction($params);
            $paymentUrl = $snapResponse->redirect_url;
            
            // Set payment expiry (24 hours)
            $paymentExpiry = now()->addHours(24);

            // Update transaksi
            $transaksi->update([
                'snap_token' => $snapToken,
                'payment_url' => $paymentUrl,
                'payment_expiry' => $paymentExpiry,
                'transaction_id' => $transaksi->no_transaksi,
            ]);

            return [
                'success' => true,
                'snap_token' => $snapToken,
                'payment_url' => $paymentUrl,
            ];

        } catch (\Exception $e) {
            Log::error('Midtrans Create Transaction Error: ' . $e->getMessage(), [
                'transaksi_id' => $transaksi->id,
                'no_transaksi' => $transaksi->no_transaksi,
            ]);

            throw new \Exception('Gagal membuat transaksi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Build transaction parameters for Midtrans
     */
    protected function buildTransactionParams(TransaksiPenerimaan $transaksi)
    {
        $itemDetails = [
            [
                'id' => $transaksi->jenisZakat->kode ?? 'ZAKAT',
                'price' => (int) $transaksi->jumlah,
                'quantity' => 1,
                'name' => $this->getItemName($transaksi),
            ]
        ];

        // Add breakdown for zakat fitrah
        if ($transaksi->isZakatFitrah() && $transaksi->jumlah_jiwa) {
            $itemDetails[0]['name'] .= ' (' . $transaksi->jumlah_jiwa . ' jiwa)';
        }

        $params = [
            'transaction_details' => [
                'order_id' => $transaksi->no_transaksi,
                'gross_amount' => (int) $transaksi->jumlah,
            ],
            'customer_details' => [
                'first_name' => $transaksi->muzakki_nama,
                'email' => $transaksi->muzakki_email ?? 'noemail@' . $transaksi->masjid->kode_masjid . '.com',
                'phone' => $transaksi->muzakki_telepon ?? '08123456789',
            ],
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => route('midtrans.finish'),
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 24
            ],
        ];

        // Enable payment methods based on metode_pembayaran
        if ($transaksi->metode_pembayaran === 'qris') {
            $params['enabled_payments'] = ['gopay', 'shopeepay', 'qris'];
        } elseif ($transaksi->metode_pembayaran === 'transfer') {
            $params['enabled_payments'] = ['bank_transfer', 'echannel', 'bca_va', 'bni_va', 'bri_va', 'permata_va'];
        }

        return $params;
    }

    /**
     * Get item name for transaction
     */
    protected function getItemName(TransaksiPenerimaan $transaksi)
    {
        $name = $transaksi->jenisZakat->nama ?? 'Zakat';
        
        if ($transaksi->tipeZakat) {
            $name .= ' - ' . $transaksi->tipeZakat->nama;
        }
        
        if ($transaksi->programZakat) {
            $name .= ' (' . $transaksi->programZakat->nama_program . ')';
        }

        return $name;
    }

    /**
     * Handle Midtrans notification webhook
     */
    public function handleNotification(array $payload)
    {
        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;
            $orderId = $notification->order_id;
            
            Log::info('Midtrans Notification Received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
            ]);

            // Find transaksi
            $transaksi = TransaksiPenerimaan::where('no_transaksi', $orderId)->first();

            if (!$transaksi) {
                Log::error('Transaksi not found for order_id: ' . $orderId);
                return [
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ];
            }

            // Update payment details
            $transaksi->payment_details = $payload;
            $transaksi->payment_type = $notification->payment_type ?? null;

            // Handle different transaction statuses
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $transaksi->markAsPaid($payload);
                }
            } elseif ($transactionStatus == 'settlement') {
                $transaksi->markAsPaid($payload);
            } elseif ($transactionStatus == 'pending') {
                $transaksi->payment_status = 'menunggu_pembayaran';
                $transaksi->save();
            } elseif ($transactionStatus == 'deny') {
                $transaksi->markAsFailed();
            } elseif ($transactionStatus == 'expire') {
                $transaksi->markAsExpired();
            } elseif ($transactionStatus == 'cancel') {
                $transaksi->markAsCancelled();
            }

            Log::info('Transaksi updated successfully', [
                'no_transaksi' => $orderId,
                'status' => $transaksi->status,
                'payment_status' => $transaksi->payment_status,
            ]);

            return [
                'success' => true,
                'message' => 'Notifikasi berhasil diproses',
                'transaksi' => $transaksi
            ];

        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage(), [
                'payload' => $payload
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Check transaction status from Midtrans
     */
    public function checkTransactionStatus($orderId)
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);
            
            return [
                'success' => true,
                'status' => $status
            ];

        } catch (\Exception $e) {
            Log::error('Midtrans Check Status Error: ' . $e->getMessage(), [
                'order_id' => $orderId
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Check if Midtrans is configured for masjid
     */
    public static function isConfigured($masjidId)
    {
        $config = KonfigurasiMidtrans::where('masjid_id', $masjidId)
            ->where('is_active', true)
            ->first();

        return $config && $config->isConfigurationComplete();
    }
}