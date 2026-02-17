<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'uuid',
        'pengguna_id',
        'peran',
        'aktivitas',
        'modul',
        'deskripsi',
        'data_lama',
        'data_baru',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method untuk auto-generate UUID
     */
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
     * Relasi ke model Pengguna
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    /**
     * Scope untuk filter berdasarkan aktivitas
     */
    public function scopeByAktivitas($query, $aktivitas)
    {
        return $query->where('aktivitas', $aktivitas);
    }

    /**
     * Scope untuk filter berdasarkan modul
     */
    public function scopeByModul($query, $modul)
    {
        return $query->where('modul', $modul);
    }

    /**
     * Scope untuk filter berdasarkan peran
     */
    public function scopeByPeran($query, $peran)
    {
        return $query->where('peran', $peran);
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('created_at', $tanggal);
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('aktivitas', 'like', "%{$search}%")
              ->orWhere('modul', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%")
              ->orWhere('peran', 'like', "%{$search}%")
              ->orWhereHas('pengguna', function ($q) use ($search) {
                  $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Helper untuk mendapatkan nama pengguna
     */
    public function getNamaPenggunaAttribute()
    {
        return $this->pengguna?->nama_lengkap ?? 'Sistem';
    }

    /**
     * Helper untuk mendapatkan email pengguna
     */
    public function getEmailPenggunaAttribute()
    {
        return $this->pengguna?->email ?? null;
    }

    /**
     * Helper untuk format aktivitas
     */
    public function getFormattedAktivitasAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->aktivitas));
    }

    /**
     * Helper untuk format modul
     */
    public function getFormattedModulAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->modul));
    }

    /**
     * Helper untuk mendapatkan badge color berdasarkan aktivitas
     */
    public function getBadgeColorAttribute()
    {
        return match($this->aktivitas) {
            'login' => 'bg-blue-100 text-blue-700',
            'logout' => 'bg-gray-100 text-gray-700',
            'create' => 'bg-green-100 text-green-700',
            'update' => 'bg-amber-100 text-amber-700',
            'delete' => 'bg-red-100 text-red-700',
            'approve' => 'bg-purple-100 text-purple-700',
            'view' => 'bg-cyan-100 text-cyan-700',
            default => 'bg-gray-100 text-gray-700'
        };
    }

    /**
     * Static method untuk mencatat aktivitas
     */
    public static function catat(
        string $aktivitas,
        string $modul,
        ?string $deskripsi = null,
        ?array $dataLama = null,
        ?array $dataBaru = null,
        ?int $penggunaId = null
    ) {
        return self::create([
            'pengguna_id' => $penggunaId ?? auth()->id(),
            'peran' => auth()->user()?->peran ?? 'guest',
            'aktivitas' => $aktivitas,
            'modul' => $modul,
            'deskripsi' => $deskripsi,
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}