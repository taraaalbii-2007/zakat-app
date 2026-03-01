<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kontak extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'kontak';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'email',
        'subjek',
        'pesan',
        'user_id',
        'ip_address',
        'user_agent',
        'dibaca_at',
        'balasan',
        'dibalas_at',
    ];

    protected $casts = [
        'dibaca_at'  => 'datetime',
        'dibalas_at' => 'datetime',
    ];

    // ── Relasi ─────────────────────────────────────────────────────────────

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    public function sudahDibaca(): bool
    {
        return !is_null($this->dibaca_at);
    }

    public function sudahDibalas(): bool
    {
        return !is_null($this->dibalas_at);
    }

    public function tandaiDibaca(): void
    {
        if (is_null($this->dibaca_at)) {
            $this->update(['dibaca_at' => now()]);
        }
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeBelumDibaca($query)
    {
        return $query->whereNull('dibaca_at');
    }

    public function scopeSudahDibaca($query)
    {
        return $query->whereNotNull('dibaca_at');
    }

    public function scopeBelumDibalas($query)
    {
        return $query->whereNull('dibalas_at');
    }

    // ── Accessors ──────────────────────────────────────────────────────────

    public function getStatusBadgeAttribute(): string
    {
        if ($this->sudahDibalas()) {
            return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Dibalas</span>';
        }

        if ($this->sudahDibaca()) {
            return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Dibaca</span>';
        }

        return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">Baru</span>';
    }
}