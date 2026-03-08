<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class KonfigurasiQris extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_qris';

    protected $fillable = [
        'lembaga_id',
        'qris_image_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ✅ TAMBAHKAN ACCESSOR INI
    public function getQrisImageUrlAttribute()
    {
        if ($this->qris_image_path) {
            // Jika path adalah URL lengkap, return langsung
            if (filter_var($this->qris_image_path, FILTER_VALIDATE_URL)) {
                return $this->qris_image_path;
            }
            // Jika path adalah relative path, buat URL
            return Storage::disk('public')->url($this->qris_image_path);
        }
        return null;
    }

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class);
    }
}