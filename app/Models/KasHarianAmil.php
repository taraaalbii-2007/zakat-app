<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KasHarianAmil extends Model
{
    use HasFactory;

    protected $table = 'kas_harian_amil';

    protected $fillable = [
        'uuid',
        'amil_id',
        'masjid_id',
        'tanggal',
        'saldo_awal',
        'total_penerimaan',
        'total_penyaluran',
        'saldo_akhir',
        'jumlah_transaksi_masuk',
        'jumlah_transaksi_keluar',
        'jumlah_penjemputan',
        'jumlah_datang_langsung',
        'status',
        'closed_at',
        'catatan',
    ];

    protected $casts = [
        'tanggal'               => 'date',
        'saldo_awal'            => 'decimal:2',
        'total_penerimaan'      => 'decimal:2',
        'total_penyaluran'      => 'decimal:2',
        'saldo_akhir'           => 'decimal:2',
        'jumlah_transaksi_masuk'  => 'integer',
        'jumlah_transaksi_keluar' => 'integer',
        'jumlah_penjemputan'    => 'integer',
        'jumlah_datang_langsung'=> 'integer',
        'closed_at'             => 'datetime',
    ];

    // ============================================
    // BOOT — UUID Auto Generate
    // ============================================
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================
    public function amil()
    {
        return $this->belongsTo(Amil::class, 'amil_id');
    }

    public function masjid()
    {
        return $this->belongsTo(Masjid::class, 'masjid_id');
    }

    // ============================================
    // SCOPES
    // ============================================
    public function scopeByMasjid($query, int $masjidId)
    {
        return $query->where('masjid_id', $masjidId);
    }

    public function scopeByAmil($query, int $amilId)
    {
        return $query->where('amil_id', $amilId);
    }

    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeByPeriode($query, string $start, string $end)
    {
        return $query->whereBetween('tanggal', [$start, $end]);
    }

    // ============================================
    // ACCESSORS
    // ============================================
    public function getIsOpenAttribute(): bool
    {
        return $this->status === 'open';
    }

    public function getIsClosedAttribute(): bool
    {
        return $this->status === 'closed';
    }

    public function getSaldoAwalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->saldo_awal, 0, ',', '.');
    }

    public function getTotalPenerimaanFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_penerimaan, 0, ',', '.');
    }

    public function getTotalPenyaluranFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_penyaluran, 0, ',', '.');
    }

    public function getSaldoAkhirFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->saldo_akhir, 0, ',', '.');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'open'   => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">Open</span>',
            'closed' => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">Closed</span>',
            default  => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">-</span>',
        };
    }

    // ============================================
    // HELPERS
    // ============================================

    /**
     * Hitung ulang saldo_akhir berdasarkan kolom lainnya
     */
    public function hitungSaldoAkhir(): void
    {
        $this->saldo_akhir = $this->saldo_awal + $this->total_penerimaan - $this->total_penyaluran;
    }

    /**
     * Tutup kas hari ini
     */
    public function tutupKas(): void
    {
        $this->hitungSaldoAkhir();
        $this->status    = 'closed';
        $this->closed_at = now();
        $this->save();
    }

    /**
     * Buka kembali kas
     */
    public function bukaKas(): void
    {
        $this->status    = 'open';
        $this->closed_at = null;
        $this->save();
    }

    /**
     * Ambil kas hari ini milik amil tertentu, atau buat baru jika belum ada
     */
    public static function kasHariIni(int $amilId, int $masjidId): ?self
    {
        return static::where('amil_id', $amilId)
            ->where('masjid_id', $masjidId)
            ->whereDate('tanggal', today())
            ->first();
    }

    /**
     * Ambil saldo_akhir dari hari sebelumnya sebagai saldo_awal hari ini
     */
    public static function getSaldoAwalHariIni(int $amilId, int $masjidId): float
    {
        $kemarin = static::where('amil_id', $amilId)
            ->where('masjid_id', $masjidId)
            ->where('tanggal', '<', today())
            ->orderByDesc('tanggal')
            ->first();

        return $kemarin ? (float) $kemarin->saldo_akhir : 0;
    }

    /**
     * Update ringkasan kas berdasarkan transaksi (dipanggil setiap ada transaksi baru)
     */
    public function updateDariTransaksiPenerimaan(float $jumlah, string $metodePenerimaan): void
    {
        $this->total_penerimaan += $jumlah;
        $this->jumlah_transaksi_masuk += 1;

        if ($metodePenerimaan === 'dijemput') {
            $this->jumlah_penjemputan += 1;
        } else {
            $this->jumlah_datang_langsung += 1;
        }

        $this->hitungSaldoAkhir();
        $this->save();
    }

    public function updateDariTransaksiPenyaluran(float $jumlah): void
    {
        $this->total_penyaluran += $jumlah;
        $this->jumlah_transaksi_keluar += 1;
        $this->hitungSaldoAkhir();
        $this->save();
    }
}