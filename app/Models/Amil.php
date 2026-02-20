<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\Loggable;

class Amil extends Model
{
    use HasFactory;
    use HasFactory;

    protected $table = 'amil';
    protected $primaryKey = 'id';

    protected $fillable = [
        'uuid',
        'pengguna_id',
        'masjid_id',
        'kode_amil',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'telepon',
        'email',
        'foto',
        'tanda_tangan',          // ← TAMBAHAN
        'tanggal_mulai_tugas',
        'tanggal_selesai_tugas',
        'status',
        'wilayah_tugas',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_lahir'         => 'date',
        'tanggal_mulai_tugas'   => 'date',
        'tanggal_selesai_tugas' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function ($amil) {
            if (empty($amil->uuid)) {
                $amil->uuid = (string) Str::uuid();
            }
        });
    }

    // ===============================
    // RELATIONSHIPS
    // ===============================

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function transaksiPenerimaan()
    {
        return $this->hasMany(TransaksiPenerimaan::class, 'amil_id');
    }

    // ===============================
    // SCOPES
    // ===============================

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByMasjid($query, $masjidId = null)
    {
        if ($masjidId) {
            return $query->where('masjid_id', $masjidId);
        }
        return $query;
    }

    // ===============================
    // ACCESSORS
    // ===============================

    public function getUmurAttribute()
    {
        if (!$this->tanggal_lahir) return null;
        return $this->tanggal_lahir->age;
    }

    public function getMasaTugasAttribute()
    {
        if (!$this->tanggal_mulai_tugas) return null;

        $start  = $this->tanggal_mulai_tugas;
        $end    = $this->tanggal_selesai_tugas ?? now();
        $years  = $end->diffInYears($start);
        $months = $end->diffInMonths($start) % 12;

        if ($years > 0) {
            return $years . ' tahun' . ($months > 0 ? ' ' . $months . ' bulan' : '');
        }
        return $months . ' bulan';
    }

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return Storage::url($this->foto);
        }
        return $this->jenis_kelamin === 'P'
            ? asset('images/default-avatar-female.png')
            : asset('images/default-avatar-male.png');
    }

    /**
     * URL tanda tangan — null jika belum diupload
     */
    public function getTandaTanganUrlAttribute(): ?string
    {
        if ($this->tanda_tangan && Storage::disk('public')->exists($this->tanda_tangan)) {
            return Storage::url($this->tanda_tangan);
        }
        return null;
    }

    public function getInitialAttribute(): string
    {
        return strtoupper(substr($this->nama_lengkap, 0, 1));
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}