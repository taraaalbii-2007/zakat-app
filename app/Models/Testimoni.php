<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Testimoni extends Model
{
    protected $table = 'testimoni';

    protected $fillable = [
        'uuid',
        'muzakki_id',
        'transaksi_penerimaan_id',
        'nama_pengirim',
        'pekerjaan',
        'isi_testimoni',
        'rating',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'rating'      => 'integer',
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

    // ── Relasi ──
    public function muzakki()
    {
        return $this->belongsTo(Muzakki::class, 'muzakki_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenerimaan::class, 'transaksi_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Pengguna::class, 'approved_by');
    }

    // ── Scopes ──
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    // ── Accessor: inisial nama ──
    public function getInisialAttribute(): string
    {
        $parts = explode(' ', $this->nama_pengirim);
        return strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
    }
}