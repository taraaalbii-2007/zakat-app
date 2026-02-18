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
        'status',
        'catatan',
        'foto_kegiatan',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'target_dana'     => 'decimal:2',
        'foto_kegiatan'   => 'array',
    ];

    // ============================================================
    // BOOT
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

    public function masjid()
    {
        return $this->belongsTo(Masjid::class, 'masjid_id');
    }

    /**
     * Transaksi Penerimaan yang terhubung ke program ini.
     * Hanya yang berstatus "verified" dihitung sebagai realisasi dana.
     */
    public function transaksiPenerimaan()
    {
        return $this->hasMany(TransaksiPenerimaan::class, 'program_zakat_id');
    }

    /**
     * Transaksi Penyaluran yang terhubung ke program ini.
     * Hanya yang berstatus "disalurkan" / "disetujui" dihitung sebagai realisasi mustahik.
     */
    public function transaksiPenyaluran()
    {
        return $this->hasMany(TransaksiPenyaluran::class, 'program_zakat_id');
    }

    // ============================================================
    // ACCESSORS — REALISASI (dihitung langsung dari transaksi)
    // ============================================================

    /**
     * Total dana yang sudah diterima (verified) untuk program ini.
     */
    public function getRealisasiDanaAttribute(): float
    {
        // Jika relasi sudah di-load (eager load), pakai collection filter.
        // Jika belum, jalankan query langsung.
        if ($this->relationLoaded('transaksiPenerimaan')) {
            return (float) $this->transaksiPenerimaan
                ->where('status', 'verified')
                ->sum('jumlah');
        }

        return (float) TransaksiPenerimaan::where('program_zakat_id', $this->id)
            ->where('status', 'verified')
            ->sum('jumlah');
    }

    /**
     * Jumlah mustahik unik yang sudah menerima penyaluran dari program ini.
     * Dihitung dari transaksi penyaluran dengan status "disetujui" atau "disalurkan".
     */
    public function getRealisasiMustahikAttribute(): int
    {
        if ($this->relationLoaded('transaksiPenyaluran')) {
            return $this->transaksiPenyaluran
                ->whereIn('status', ['disetujui', 'disalurkan'])
                ->pluck('mustahik_id')
                ->unique()
                ->count();
        }

        return (int) TransaksiPenyaluran::where('program_zakat_id', $this->id)
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->distinct('mustahik_id')
            ->count('mustahik_id');
    }

    // ============================================================
    // ACCESSORS — PROGRESS
    // ============================================================

    public function getProgressDanaAttribute(): float
    {
        if (!$this->target_dana || $this->target_dana == 0) {
            return 0;
        }
        return min(100, round(($this->realisasi_dana / $this->target_dana) * 100, 1));
    }

    public function getProgressMustahikAttribute(): float
    {
        if (!$this->target_mustahik || $this->target_mustahik == 0) {
            return 0;
        }
        return min(100, round(($this->realisasi_mustahik / $this->target_mustahik) * 100, 1));
    }

    // ============================================================
    // ACCESSORS — BADGE
    // ============================================================

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'draft'      => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Draft</span>',
            'aktif'      => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>',
            'selesai'    => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Selesai</span>',
            'dibatalkan' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>',
        ];

        return $badges[$this->status] ?? '';
    }

    // ============================================================
    // SCOPES
    // ============================================================

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

    // ============================================================
    // HELPERS
    // ============================================================

    public static function generateKodeProgram($masjidId): string
    {
        $masjid     = Masjid::find($masjidId);
        $tahun      = date('Y');
        $kodeMasjid = $masjid->kode_masjid ?? 'MSJ';

        return "PROG-{$kodeMasjid}-{$tahun}";
    }
}