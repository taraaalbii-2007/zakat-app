<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Muzakki extends Model
{
    use HasFactory;

    protected $table = 'muzakki';

    protected $fillable = [
        'uuid',
        'pengguna_id',
        'masjid_id',
        'nama',
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

    /**
     * Relasi ke tabel pengguna (akun login)
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    /**
     * Relasi ke tabel masjid
     */
    public function masjid()
    {
        return $this->belongsTo(Masjid::class, 'masjid_id');
    }

    /**
     * Relasi ke transaksi penerimaan (riwayat zakat)
     */
    public function transaksiPenerimaan()
    {
        return $this->hasMany(TransaksiPenerimaan::class, 'muzakki_id');
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    /**
     * URL foto profil (dengan fallback ke placeholder)
     */
    public function getFotoUrlAttribute(): string
    {
        if ($this->foto && \Storage::disk('public')->exists($this->foto)) {
            return asset('storage/' . $this->foto);
        }

        // Placeholder dengan inisial nama
        $initials = collect(explode(' ', $this->nama))
            ->take(2)
            ->map(fn($word) => strtoupper($word[0]))
            ->join('');

        return 'https://ui-avatars.com/api/?name=' . urlencode($initials)
            . '&background=1565c0&color=ffffff&size=128&bold=true';
    }

    /**
     * Nama singkat (untuk tampilan terbatas)
     */
    public function getNamaSingkatAttribute(): string
    {
        $parts = explode(' ', $this->nama);
        return count($parts) > 1
            ? $parts[0] . ' ' . $parts[count($parts) - 1]
            : $this->nama;
    }

    /**
     * Telepon ter-mask untuk keamanan (0812****5678)
     */
    public function getTeleponMaskAttribute(): string
    {
        if (strlen($this->telepon) < 8) {
            return $this->telepon;
        }

        return substr($this->telepon, 0, 4)
            . str_repeat('*', strlen($this->telepon) - 8)
            . substr($this->telepon, -4);
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Hanya muzakki yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Filter berdasarkan masjid
     */
    public function scopeByMasjid($query, int $masjidId)
    {
        return $query->where('masjid_id', $masjidId);
    }

    /**
     * Muzakki yang punya akun (terhubung ke pengguna)
     */
    public function scopeBerakun($query)
    {
        return $query->whereNotNull('pengguna_id');
    }

    /**
     * Search by nama, telepon, atau email
     */
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

    /**
     * Cek apakah muzakki punya akun login
     */
    public function hasAkun(): bool
    {
        return !is_null($this->pengguna_id);
    }

    /**
     * Total nominal zakat yang pernah dibayar
     */
    public function totalZakat(): int
    {
        return $this->transaksiPenerimaan()
            ->where('status', 'verified')
            ->sum('jumlah_bayar');
    }

    /**
     * Jumlah transaksi zakat
     */
    public function jumlahTransaksi(): int
    {
        return $this->transaksiPenerimaan()->count();
    }

    /**
     * Transaksi zakat terakhir
     */
    public function transaksiTerakhir()
    {
        return $this->transaksiPenerimaan()
            ->latest()
            ->first();
    }
}