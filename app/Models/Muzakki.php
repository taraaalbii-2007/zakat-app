<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Muzakki extends Model
{
    use HasFactory;

    protected $table = 'muzakki';

    protected $fillable = [
        'uuid',
        'pengguna_id',
        'lembaga_id',
        'nama',
        'jenis_kelamin',   // ← BARU
        'telepon',
        'email',
        'alamat',
        'nik',
        'foto',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ============================================================
    // BOOT — Auto generate UUID
    // ============================================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class, 'lembaga_id');
    }

    public function transaksiPenerimaan()
    {
        return $this->hasMany(TransaksiPenerimaan::class, 'muzakki_id');
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return asset('storage/' . $this->foto);
        }

        $initials = collect(explode(' ', $this->nama))
            ->take(2)
            ->map(fn($word) => strtoupper($word[0]))
            ->join('');

        // Warna berbeda berdasar jenis kelamin
        $bg = $this->jenis_kelamin === 'perempuan' ? 'c2185b' : '1565c0';

        return 'https://ui-avatars.com/api/?name=' . urlencode($initials)
            . '&background=' . $bg . '&color=ffffff&size=128&bold=true';
    }

    public function getNamaSingkatAttribute(): string
    {
        $parts = explode(' ', $this->nama);
        return count($parts) > 1
            ? $parts[0] . ' ' . $parts[count($parts) - 1]
            : $this->nama;
    }

    public function getTeleponMaskAttribute(): string
    {
        if (strlen($this->telepon) < 8) {
            return $this->telepon;
        }

        return substr($this->telepon, 0, 4)
            . str_repeat('*', strlen($this->telepon) - 8)
            . substr($this->telepon, -4);
    }

    /**
     * Label jenis kelamin yang mudah dibaca
     */
    public function getJenisKelaminLabelAttribute(): string
    {
        return match ($this->jenis_kelamin) {
            'laki-laki'  => 'Laki-laki',
            'perempuan'  => 'Perempuan',
            default      => '-',
        };
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLembaga($query, int $lembagaId)
    {
        return $query->where('lembaga_id', $lembagaId);
    }

    public function scopeBerakun($query)
    {
        return $query->whereNotNull('pengguna_id');
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama', 'like', '%' . $keyword . '%')
              ->orWhere('telepon', 'like', '%' . $keyword . '%')
              ->orWhere('email', 'like', '%' . $keyword . '%')
              ->orWhere('nik', 'like', '%' . $keyword . '%');
        });
    }

    // ============================================================
    // HELPERS
    // ============================================================

    public function hasAkun(): bool
    {
        return !is_null($this->pengguna_id);
    }

    public function totalZakat(): int
    {
        return $this->transaksiPenerimaan()
            ->where('status', 'verified')
            ->sum('jumlah_bayar');
    }

    public function jumlahTransaksi(): int
    {
        return $this->transaksiPenerimaan()->count();
    }

    public function transaksiTerakhir()
    {
        return $this->transaksiPenerimaan()
            ->latest()
            ->first();
    }
}