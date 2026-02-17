<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleConfig extends Model
{
    use HasFactory;

    protected $table = 'google_configs';

    protected $fillable = [
        'GOOGLE_CLIENT_ID',
        'GOOGLE_CLIENT_SECRET',
        'GOOGLE_REDIRECT_URI'
    ];

    /**
     * Relasi ke KonfigurasiAplikasi
     */
    public function konfigurasiAplikasi()
    {
        return $this->belongsTo(KonfigurasiAplikasi::class);
    }

    /**
     * Cek apakah konfigurasi Google OAuth sudah lengkap
     */
    public function isComplete(): bool
    {
        return !empty($this->GOOGLE_CLIENT_ID) 
            && !empty($this->GOOGLE_CLIENT_SECRET);
    }

    /**
     * Get Google config sebagai array untuk Laravel config
     */
    public function toConfigArray(): array
    {
        return [
            'services.google.client_id' => $this->GOOGLE_CLIENT_ID,
            'services.google.client_secret' => $this->GOOGLE_CLIENT_SECRET,
            'services.google.redirect' => $this->GOOGLE_REDIRECT_URI ?? url('/auth/google/callback'),
        ];
    }

    /**
     * Validate redirect URI format
     */
    public function hasValidRedirectUri(): bool
    {
        if (empty($this->GOOGLE_REDIRECT_URI)) {
            return true; // Will use default
        }
        
        return filter_var($this->GOOGLE_REDIRECT_URI, FILTER_VALIDATE_URL) !== false;
    }
}