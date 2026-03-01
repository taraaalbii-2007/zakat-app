<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriBulletin extends Model
{
    protected $table = 'kategori_bulletin';

    protected $fillable = [
        'uuid',
        'nama_kategori',
    ];

    // ============================================
    // BOOT
    // ============================================
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================
    public function bulletins()
    {
        return $this->hasMany(Bulletin::class, 'kategori_bulletin_id');
    }

    // ============================================
    // SCOPES
    // ============================================
    public function scopeSearch($query, $keyword)
    {
        return $query->where('nama_kategori', 'like', "%{$keyword}%");
    }

    // Tambahkan di KategoriBulletin.php
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}