<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigurasiWhatsapp extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_whatsapp';

    protected $fillable = [
        'masjid_id',
        'api_key',
        'nomor_pengirim',
        'api_url',
        'nomor_tujuan_default',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'api_url' => 'https://api.fonnte.com/send',
        'is_active' => false,
    ];

    // Relationship
    public function masjid()
    {
        return $this->belongsTo(Masjid::class, 'masjid_id');
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Tidak Aktif';
    }

    // Accessor untuk status badge class
    public function getStatusBadgeClassAttribute()
    {
        return $this->is_active 
            ? 'bg-green-100 text-green-800' 
            : 'bg-gray-100 text-gray-800';
    }

    // Check if configuration is complete
    public function isConfigurationComplete()
    {
        return !empty($this->api_key) 
            && !empty($this->nomor_pengirim);
    }

    // Format nomor WhatsApp
    public function getFormattedNomorPengirimAttribute()
    {
        if (empty($this->nomor_pengirim)) {
            return null;
        }

        $nomor = preg_replace('/[^0-9]/', '', $this->nomor_pengirim);
        
        // Jika dimulai dengan 0, ganti dengan 62
        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        }
        
        return $nomor;
    }

    // Scope untuk filter by masjid
    public function scopeByMasjid($query, $masjidId)
    {
        return $query->where('masjid_id', $masjidId);
    }

    // Scope untuk filter active
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}