<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class MailConfig extends Model
{
    use HasFactory;

    protected $table = 'mail_configs';

    protected $fillable = [
        'MAIL_MAILER',
        'MAIL_HOST',
        'MAIL_PORT',
        'MAIL_USERNAME',
        'MAIL_PASSWORD',
        'MAIL_ENCRYPTION',
        'MAIL_FROM_ADDRESS',
        'MAIL_FROM_NAME'
    ];

    /**
     * Encrypt password saat disimpan
     */
    public function setMailPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['MAIL_PASSWORD'] = Crypt::encryptString($value);
        }
    }

    /**
     * Decrypt password saat diambil
     */
    public function getMailPasswordAttribute($value)
    {
        if ($value) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                // Jika gagal decrypt, return value asli (untuk backward compatibility)
                return $value;
            }
        }
        return null;
    }

    /**
     * Relasi ke KonfigurasiAplikasi
     */
    public function konfigurasiAplikasi()
    {
        return $this->belongsTo(KonfigurasiAplikasi::class);
    }

    /**
     * Cek apakah konfigurasi mail sudah lengkap
     */
    public function isComplete(): bool
    {
        return !empty($this->MAIL_HOST) 
            && !empty($this->MAIL_PORT) 
            && !empty($this->MAIL_USERNAME) 
            && !empty($this->MAIL_PASSWORD)
            && !empty($this->MAIL_FROM_ADDRESS);
    }

    /**
     * Get mail config sebagai array untuk Laravel config
     */
    public function toConfigArray(): array
    {
        return [
            'mail.mailers.smtp.host' => $this->MAIL_HOST,
            'mail.mailers.smtp.port' => $this->MAIL_PORT ?? 587,
            'mail.mailers.smtp.encryption' => $this->MAIL_ENCRYPTION ?? 'tls',
            'mail.mailers.smtp.username' => $this->MAIL_USERNAME,
            'mail.mailers.smtp.password' => $this->MAIL_PASSWORD, // Sudah di-decrypt otomatis
            'mail.from.address' => $this->MAIL_FROM_ADDRESS,
            'mail.from.name' => $this->MAIL_FROM_NAME ?? config('app.name'),
        ];
    }
}