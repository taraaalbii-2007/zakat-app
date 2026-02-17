<?php
// app/Models/ProgramZakat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProgramZakat extends Model
{
    use HasFactory;

    protected $table = 'program_zakat';

    protected $fillable = [
        'uuid',
        'masjid_id',
        'nama_program',
        'kode_program',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'target_dana',
        'target_mustahik',
        'realisasi_dana',
        'realisasi_mustahik',
        'status',
        'catatan',
        'foto_kegiatan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'target_dana' => 'decimal:2',
        'realisasi_dana' => 'decimal:2',
        'foto_kegiatan' => 'array',
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

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class, 'masjid_id');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Draft</span>',
            'aktif' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>',
            'selesai' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Selesai</span>',
            'dibatalkan' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>',
        ];

        return $badges[$this->status] ?? '';
    }

    public function getProgressDanaAttribute()
    {
        if (!$this->target_dana || $this->target_dana == 0) {
            return 0;
        }
        return min(100, round(($this->realisasi_dana / $this->target_dana) * 100, 2));
    }

    public function getProgressMustahikAttribute()
    {
        if (!$this->target_mustahik || $this->target_mustahik == 0) {
            return 0;
        }
        return min(100, round(($this->realisasi_mustahik / $this->target_mustahik) * 100, 2));
    }

    // Scopes
    public function scopeByMasjid($query, $masjidId)
    {
        return $query->where('masjid_id', $masjidId);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama_program', 'like', "%{$search}%")
              ->orWhere('kode_program', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%");
        });
    }

    // Helper Methods
    public static function generateKodeProgram($masjidId)
    {
        $masjid = Masjid::find($masjidId);
        $tahun = date('Y');
        $kodeMasjid = $masjid->kode_masjid ?? 'MSJ';
        
        return "PROG-{$kodeMasjid}-{$tahun}";
    }
}