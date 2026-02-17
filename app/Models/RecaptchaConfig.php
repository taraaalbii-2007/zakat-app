<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecaptchaConfig extends Model
{
    use HasFactory;

    protected $table = 'recaptcha_configs';

    protected $fillable = [
        'RECAPTCHA_SITE_KEY',
        'RECAPTCHA_SECRET_KEY'
    ];

    /**
     * Relasi ke KonfigurasiAplikasi
     */
    public function konfigurasiAplikasi()
    {
        return $this->belongsTo(KonfigurasiAplikasi::class);
    }

    /**
     * Cek apakah konfigurasi reCAPTCHA sudah lengkap
     */
    public function isComplete(): bool
    {
        return !empty($this->RECAPTCHA_SITE_KEY) 
            && !empty($this->RECAPTCHA_SECRET_KEY);
    }

    /**
     * Get reCAPTCHA config sebagai array untuk Laravel config
     */
    public function toConfigArray(): array
    {
        return [
            'recaptcha.site_key' => $this->RECAPTCHA_SITE_KEY,
            'recaptcha.secret_key' => $this->RECAPTCHA_SECRET_KEY,
        ];
    }

    /**
     * Check if reCAPTCHA is enabled (has valid keys)
     */
    public function isEnabled(): bool
    {
        return $this->isComplete();
    }
}