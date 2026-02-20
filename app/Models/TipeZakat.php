<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\Loggable;

class TipeZakat extends Model
{
    use HasFactory;
    use Loggable;

    protected $table = 'tipe_zakat';

    protected $fillable = [
        'uuid',
        'jenis_zakat_id',
        'nama',
        'nisab_emas_gram',
        'nisab_perak_gram',
        'nisab_pertanian_kg',
        'nisab_kambing_min',
        'nisab_sapi_min',
        'nisab_unta_min',
        'persentase_zakat',
        'persentase_alternatif',
        'keterangan_persentase',
        'requires_haul',
        'ketentuan_khusus',
    ];

    protected $casts = [
        'nisab_emas_gram' => 'decimal:2',
        'nisab_perak_gram' => 'decimal:2',
        'nisab_pertanian_kg' => 'decimal:2',
        'nisab_kambing_min' => 'integer',
        'nisab_sapi_min' => 'integer',
        'nisab_unta_min' => 'integer',
        'persentase_zakat' => 'decimal:2',
        'persentase_alternatif' => 'decimal:2',
        'requires_haul' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Relasi ke Jenis Zakat (Parent)
     */
    public function jenisZakat()
    {
        return $this->belongsTo(JenisZakat::class, 'jenis_zakat_id');
    }

    /**
     * Scope untuk filter berdasarkan jenis zakat
     */
    public function scopeByJenisZakat($query, $jenisZakatId)
    {
        return $query->where('jenis_zakat_id', $jenisZakatId);
    }

    /**
     * Scope untuk yang memerlukan haul
     */
    public function scopeRequiresHaul($query)
    {
        return $query->where('requires_haul', true);
    }

    /**
     * Cek apakah tipe zakat ini berbasis emas/perak
     */
    public function isBerbasisEmasPerak(): bool
    {
        return !is_null($this->nisab_emas_gram) || !is_null($this->nisab_perak_gram);
    }

    /**
     * Cek apakah tipe zakat ini berbasis pertanian
     */
    public function isBerbasisPertanian(): bool
    {
        return !is_null($this->nisab_pertanian_kg);
    }

    /**
     * Cek apakah tipe zakat ini berbasis peternakan
     */
    public function isBerbasisPeternakan(): bool
    {
        return !is_null($this->nisab_kambing_min) || 
               !is_null($this->nisab_sapi_min) || 
               !is_null($this->nisab_unta_min);
    }

    /**
     * Format persentase dengan simbol %
     */
    public function getFormattedPersentaseAttribute()
    {
        if ($this->persentase_zakat) {
            return number_format($this->persentase_zakat, 2) . '%';
        }
        return '-';
    }

    /**
     * Mendapatkan semua tipe nisab yang aktif
     */
    public function getActiveNisabTypesAttribute()
    {
        $types = [];
        
        if ($this->nisab_emas_gram) {
            $types[] = 'Emas: ' . number_format($this->nisab_emas_gram, 2) . ' gram';
        }
        if ($this->nisab_perak_gram) {
            $types[] = 'Perak: ' . number_format($this->nisab_perak_gram, 2) . ' gram';
        }
        if ($this->nisab_pertanian_kg) {
            $types[] = 'Pertanian: ' . number_format($this->nisab_pertanian_kg, 2) . ' kg';
        }
        if ($this->nisab_kambing_min) {
            $types[] = 'Kambing: min ' . number_format($this->nisab_kambing_min) . ' ekor';
        }
        if ($this->nisab_sapi_min) {
            $types[] = 'Sapi: min ' . number_format($this->nisab_sapi_min) . ' ekor';
        }
        if ($this->nisab_unta_min) {
            $types[] = 'Unta: min ' . number_format($this->nisab_unta_min) . ' ekor';
        }
        
        return $types;
    }
}