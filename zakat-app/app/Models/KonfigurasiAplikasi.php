<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigurasiAplikasi extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_aplikasi';

    protected $fillable = [
        'uuid',
        'nama_aplikasi',
        'tagline',
        'deskripsi_aplikasi',
        'logo_aplikasi',
        'favicon',
        'email_support',
        'telepon_support',
        'facebook_url',
        'instagram_url',
        'whatsapp_support'
    ];

    protected $casts = [
        'uuid' => 'string'
    ];

    /**
     * Boot method untuk generate UUID otomatis
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    /**
     * Mendapatkan konfigurasi pertama atau buat default
     */
    public static function getConfig()
    {
        $config = self::first();
        
        if (!$config) {
            $config = self::create([
                'nama_aplikasi' => 'Sistem Zakat Digital',
                'tagline' => 'Membantu Pengelolaan Zakat Digital',
                'deskripsi_aplikasi' => 'Sistem manajemen zakat digital untuk kemudahan pembayaran dan pengelolaan zakat',
                'email_support' => 'support@zakatdigital.com',
                'telepon_support' => '081234567890',
            ]);
        }
        
        return $config;
    }

    /**
     * Format nomor telepon untuk display
     */
    public function getTeleponFormattedAttribute()
    {
        if (!$this->telepon_support) return null;
        
        $phone = $this->telepon_support;
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        return $phone;
    }

    /**
     * Format WhatsApp untuk link
     */
    public function getWhatsappLinkAttribute()
    {
        if (!$this->whatsapp_support) return null;
        
        $phone = $this->telepon_support;
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        return 'https://wa.me/' . $phone;
    }
}