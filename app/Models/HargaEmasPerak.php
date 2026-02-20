<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class HargaEmasPerak extends Model
{
    use HasFactory;
    use Loggable;

    protected $table = 'harga_emas_perak';
    
    protected $fillable = [
        'uuid',
        'tanggal',
        'harga_emas_pergram',
        'harga_perak_pergram',
        'sumber',
        'keterangan',
        'is_active'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'harga_emas_pergram' => 'decimal:2',
        'harga_perak_pergram' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });

        // Nonaktifkan harga lama jika ada harga baru yang aktif untuk tanggal yang sama
        static::created(function ($model) {
            if ($model->is_active) {
                self::where('tanggal', $model->tanggal)
                    ->where('id', '!=', $model->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }
        });

        static::updated(function ($model) {
            if ($model->is_active) {
                self::where('tanggal', $model->tanggal)
                    ->where('id', '!=', $model->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFilter($query, $filters)
    {
        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('sumber', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('keterangan', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'aktif');
        }

        if (isset($filters['tanggal'])) {
            $query->whereDate('tanggal', $filters['tanggal']);
        }

        if (isset($filters['sumber'])) {
            $query->where('sumber', $filters['sumber']);
        }

        return $query;
    }

    public function getFormattedEmasAttribute()
    {
        return 'Rp ' . number_format($this->harga_emas_pergram, 0, ',', '.');
    }

    public function getFormattedPerakAttribute()
    {
        return 'Rp ' . number_format($this->harga_perak_pergram, 0, ',', '.');
    }

    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal->format('d/m/Y');
    }
}