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
        'versi',
        'logo_aplikasi',
        'favicon',
        'email_admin',
        'telepon_admin',
        'alamat_kantor',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'youtube_url',
        'whatsapp_support',
        'is_default'
    ];

    protected $casts = [
        'uuid' => 'string',
        'is_default' => 'boolean'
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
     * Mendapatkan konfigurasi default (hanya 1 row)
     */
    public static function getConfig()
    {
        $config = self::where('is_default', true)->first();
        
        if (!$config) {
            $config = self::create([
                'nama_aplikasi' => 'Sistem Zakat Digital',
                'tagline' => 'Membantu Pengelolaan Zakat Digital',
                'deskripsi_aplikasi' => 'Sistem manajemen zakat digital untuk kemudahan pembayaran dan pengelolaan zakat',
                'versi' => '1.0.0',
                'email_admin' => 'admin@zakatdigital.com',
                'telepon_admin' => '081234567890',
                'is_default' => true,
            ]);
        }
        
        return $config;
    }

    /**
     * Format nomor telepon untuk display
     */
    public function getTeleponFormattedAttribute()
    {
        if (!$this->telepon_admin) return null;
        
        $phone = $this->telepon_admin;
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
        
        $phone = $this->whatsapp_support;
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        return 'https://wa.me/' . $phone;
    }

    /**
     * Relasi ke MailConfig
     */
    public function mailConfig()
    {
        return $this->hasOne(MailConfig::class);
    }

    /**
     * Relasi ke GoogleConfig
     */
    public function googleConfig()
    {
        return $this->hasOne(GoogleConfig::class);
    }

    /**
     * Relasi ke RecaptchaConfig
     */
    public function recaptchaConfig()
    {
        return $this->hasOne(RecaptchaConfig::class);
    }
}