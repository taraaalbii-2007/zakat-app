<?php
// app/Models/KunjunganMustahik.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class KunjunganMustahik extends Model
{
    protected $table = 'kunjungan_mustahik';

    protected $fillable = [
        'uuid',
        'amil_id',
        'mustahik_id',
        'tanggal_kunjungan',
        'waktu_mulai',
        'waktu_selesai',
        'tujuan',
        'hasil_kunjungan',
        'foto_dokumentasi',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'foto_dokumentasi'  => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    // ── Relasi ───────────────────────────────────────────────────────────
    public function amil()
    {
        return $this->belongsTo(Amil::class);
    }

    public function mustahik()
    {
        return $this->belongsTo(Mustahik::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────
    public function scopeByAmil($query, $amilId)
    {
        return $query->where('amil_id', $amilId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByTujuan($query, $tujuan)
    {
        return $query->where('tujuan', $tujuan);
    }

    public function scopeByTanggal($query, $dari, $sampai = null)
    {
        $query->where('tanggal_kunjungan', '>=', $dari);
        if ($sampai) {
            $query->where('tanggal_kunjungan', '<=', $sampai);
        }
        return $query;
    }

    public function scopeDirencanakan($query)
    {
        return $query->where('status', 'direncanakan');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    // ── Accessors ────────────────────────────────────────────────────────
    public function getStatusBadgeAttribute(): string
    {
        $map = [
            'direncanakan' => 'bg-blue-100 text-blue-800',
            'selesai'      => 'bg-green-100 text-green-800',
            'dibatalkan'   => 'bg-red-100 text-red-800',
        ];
        $label = [
            'direncanakan' => 'Direncanakan',
            'selesai'      => 'Selesai',
            'dibatalkan'   => 'Dibatalkan',
        ];
        $class = $map[$this->status] ?? 'bg-gray-100 text-gray-800';
        $text  = $label[$this->status] ?? $this->status;

        return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$class}\">{$text}</span>";
    }

    public function getTujuanLabelAttribute(): string
    {
        $labels = [
            'verifikasi' => 'Verifikasi',
            'penyaluran' => 'Penyaluran',
            'monitoring' => 'Monitoring',
            'lainnya'    => 'Lainnya',
        ];
        return $labels[$this->tujuan] ?? ucfirst($this->tujuan);
    }

    public function getTujuanColorAttribute(): string
    {
        $colors = [
            'verifikasi' => '#6366f1',
            'penyaluran' => '#10b981',
            'monitoring' => '#f59e0b',
            'lainnya'    => '#6b7280',
        ];
        return $colors[$this->tujuan] ?? '#6b7280';
    }

    public function getStatusColorAttribute(): string
    {
        // Warna untuk FullCalendar event
        $colors = [
            'direncanakan' => '#3b82f6',
            'selesai'      => '#10b981',
            'dibatalkan'   => '#ef4444',
        ];
        return $colors[$this->status] ?? '#6b7280';
    }

    public function getWaktuFormatAttribute(): string
    {
        if (!$this->waktu_mulai) return '-';
        $mulai = substr($this->waktu_mulai, 0, 5);
        $selesai = $this->waktu_selesai ? substr($this->waktu_selesai, 0, 5) : '?';
        return "{$mulai} – {$selesai}";
    }

    public function getFotoDokumentasiUrlsAttribute(): array
    {
        if (!$this->foto_dokumentasi) return [];
        return array_map(fn($path) => Storage::url($path), $this->foto_dokumentasi);
    }

    // ── Helpers ──────────────────────────────────────────────────────────
    public function tandaiSelesai(array $data = []): void
    {
        $this->update(array_merge([
            'status'       => 'selesai',
            'waktu_selesai' => $data['waktu_selesai'] ?? now()->format('H:i:s'),
        ], $data));
    }

    public function batalkan(): void
    {
        $this->update(['status' => 'dibatalkan']);
    }

    public function isEditable(): bool
    {
        return $this->status === 'direncanakan';
    }

    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }

    public function isDibatalkan(): bool
    {
        return $this->status === 'dibatalkan';
    }

    public function removeFotoDokumentasiByIndex(int $index): bool
    {
        $fotos = $this->foto_dokumentasi ?? [];

        if (!isset($fotos[$index])) return false;

        if (Storage::disk('public')->exists($fotos[$index])) {
            Storage::disk('public')->delete($fotos[$index]);
        }

        unset($fotos[$index]);
        $this->foto_dokumentasi = array_values($fotos);
        $this->save();

        return true;
    }

    public function clearFotoDokumentasi(): void
    {
        foreach ($this->foto_dokumentasi ?? [] as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        $this->update(['foto_dokumentasi' => null]);
    }
}