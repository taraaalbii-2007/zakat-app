<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class TransaksiPenyaluran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi_penyaluran';

    protected $fillable = [
        'uuid',
        'no_transaksi',
        'no_kwitansi',
        'tanggal_penyaluran',
        'waktu_penyaluran',
        'periode',
        'masjid_id',
        'mustahik_id',
        'kategori_mustahik_id',
        'jenis_zakat_id',
        'program_zakat_id',
        'amil_id',
        'jumlah',
        'metode_penyaluran',
        'detail_barang',
        'nilai_barang',
        'foto_bukti',
        'path_tanda_tangan',
        'keterangan',
        'status',
        'alasan_pembatalan',
        'approved_by',
        'approved_at',
        'disalurkan_oleh',
        'disalurkan_at',
        'dibatalkan_oleh',
        'dibatalkan_at',
    ];

    protected $casts = [
        'tanggal_penyaluran' => 'date',
        'jumlah'             => 'decimal:2',
        'nilai_barang'       => 'decimal:2',
        'approved_at'        => 'datetime',
        'disalurkan_at'      => 'datetime',
        'dibatalkan_at'      => 'datetime',
    ];

    // ============================================
    // BOOT — UUID & No Transaksi
    // ============================================
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->no_transaksi)) {
                $model->no_transaksi = static::generateNoTransaksi();
            }
        });
    }

    public static function generateNoTransaksi(): string
    {
        $prefix = 'SLYRAN-' . now()->format('Ymd');
        $last   = static::withTrashed()
            ->where('no_transaksi', 'like', $prefix . '-%')
            ->orderByDesc('no_transaksi')
            ->value('no_transaksi');

        $seq = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    // ============================================
    // ROUTE MODEL BINDING
    // ============================================
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function mustahik()
    {
        return $this->belongsTo(Mustahik::class);
    }

    public function kategoriMustahik()
    {
        return $this->belongsTo(KategoriMustahik::class);
    }

    public function jenisZakat()
    {
        return $this->belongsTo(JenisZakat::class);
    }

    public function programZakat()
    {
        return $this->belongsTo(ProgramZakat::class);
    }

    public function amil()
    {
        return $this->belongsTo(Amil::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(Pengguna::class, 'approved_by');
    }

    public function disalurkanOleh()
    {
        return $this->belongsTo(Pengguna::class, 'disalurkan_oleh');
    }

    public function dibatalkanOleh()
    {
        return $this->belongsTo(Pengguna::class, 'dibatalkan_oleh');
    }

    public function dokumentasi()
    {
        return $this->hasMany(DokumentasiPenyaluran::class)->orderBy('urutan');
    }

    // ============================================
    // ACCESSORS
    // ============================================

    /**
     * Jumlah terformat — barang tampilkan nilai_barang
     */
    public function getJumlahFormattedAttribute(): string
    {
        if ($this->metode_penyaluran === 'barang') {
            return $this->nilai_barang
                ? 'Rp ' . number_format($this->nilai_barang, 0, ',', '.')
                : 'Barang';
        }

        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * HTML badge status
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft'      => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">Draft</span>',
            'disetujui'  => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-200">✓ Disetujui</span>',
            'disalurkan' => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">✓ Disalurkan</span>',
            'dibatalkan' => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">✕ Dibatalkan</span>',
            default      => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">-</span>',
        };
    }

    /**
     * HTML badge metode penyaluran
     */
    public function getMetodePenyaluranBadgeAttribute(): string
    {
        return match ($this->metode_penyaluran) {
            'tunai'    => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">💵 Tunai</span>',
            'transfer' => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">🏦 Transfer</span>',
            'barang'   => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-50 text-orange-700 border border-orange-200">📦 Barang</span>',
            default    => '',
        };
    }

    /**
     * Apakah bisa diedit (hanya draft)
     */
    public function getBisaDieditAttribute(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Apakah bisa dihapus (hanya draft)
     */
    public function getBisaDihapusAttribute(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Apakah amil bisa konfirmasi disalurkan
     */
    public function getBisaDisalurkanAttribute(): bool
    {
        return $this->status === 'disetujui';
    }

    // ============================================
    // SCOPES
    // ============================================
    public function scopeByMasjid($query, int $masjidId)
    {
        return $query->where('masjid_id', $masjidId);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPeriode($query, string $periode)
    {
        return $query->where('periode', $periode);
    }

    public function scopeMenungguApproval($query)
    {
        return $query->where('status', 'draft');
    }
}