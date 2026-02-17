<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriMustahik extends Model
{
    use HasFactory;

    protected $table = 'kategori_mustahik';

    // Kolom sesuai migration: id, uuid, nama, kriteria, persentase_default, timestamps
    protected $fillable = [
        'uuid',
        'nama',
        'kriteria',
        'persentase_default',
    ];

    protected $casts = [
        // PERBAIKAN: 'decimal:2' bukan tipe cast valid di Laravel — gunakan 'float'
        'persentase_default' => 'float',
    ];

    // ─── Boot ────────────────────────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    // ─── Route Model Binding ─────────────────────────────────────────────────

    /**
     * Gunakan UUID di route agar integer ID tidak terekspos ke publik.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    /**
     * Pencarian di kolom nama dan kriteria.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama',      'like', "%{$search}%")
              ->orWhere('kriteria', 'like', "%{$search}%");
        });
    }

    public function getPersentaseFormattedAttribute(): string
    {
        return $this->persentase_default !== null
            ? number_format($this->persentase_default, 2) . '%'
            : '-';
    }

}