<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class LaporanKeuanganMasjid extends Model
{
    use HasFactory;

    protected $table = 'laporan_keuangan_masjid';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'masjid_id',
        'tahun',
        'bulan',
        'periode_mulai',
        'periode_selesai',
        'saldo_awal',
        'total_penerimaan',
        'total_penyaluran',
        'saldo_akhir',
        'detail_penerimaan',
        'detail_penyaluran',
        'jumlah_muzakki',
        'jumlah_mustahik',
        'jumlah_transaksi_masuk',
        'jumlah_transaksi_keluar',
        'status',
        'created_by',
        'published_at',
    ];

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'published_at' => 'datetime',
        'saldo_awal' => 'decimal:2',
        'total_penerimaan' => 'decimal:2',
        'total_penyaluran' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
        'detail_penerimaan' => 'array',
        'detail_penyaluran' => 'array',
        'jumlah_muzakki' => 'integer',
        'jumlah_mustahik' => 'integer',
        'jumlah_transaksi_masuk' => 'integer',
        'jumlah_transaksi_keluar' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
            if (!$model->periode_mulai) {
                $model->periode_mulai = now()->startOfMonth();
            }
            if (!$model->periode_selesai) {
                $model->periode_selesai = now()->endOfMonth();
            }
        });
    }

    // Relationships
    public function masjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'created_by');
    }

    // Accessors
    public function getNamaBulanAttribute(): string
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulan[$this->bulan] ?? 'Unknown';
    }

    public function getPeriodeAttribute(): string
    {
        return "{$this->nama_bulan} {$this->tahun}";
    }

    public function getStatusBadgeAttribute(): string
    {
        $statuses = [
            'draft' => ['bg-gray-100 text-gray-800', 'Draft'],
            'final' => ['bg-blue-100 text-blue-800', 'Final'],
            'published' => ['bg-green-100 text-green-800', 'Published']
        ];

        [$class, $text] = $statuses[$this->status] ?? ['bg-gray-100 text-gray-800', 'Unknown'];

        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $class . '">' . $text . '</span>';
    }

    public function getCanPublishAttribute(): bool
    {
        return $this->status !== 'published' && auth()->check();
    }

    public function getCanGenerateAttribute(): bool
    {
        return $this->status === 'draft' && auth()->check();
    }

    // Scope
    public function scopeFilterTahun($query, $tahun = null)
    {
        if ($tahun) {
            return $query->where('tahun', $tahun);
        }
        return $query->where('tahun', date('Y'));
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeForMasjid($query, $masjidId = null)
    {
        $masjidId = $masjidId ?? session('masjid_id');
        return $query->where('masjid_id', $masjidId);
    }

    // Methods
    public function calculateSaldoAkhir(): void
    {
        $this->saldo_akhir = $this->saldo_awal + $this->total_penerimaan - $this->total_penyaluran;
    }

    public function publish(): bool
    {
        if ($this->status === 'draft') {
            $this->status = 'published';
            $this->published_at = now();
            return $this->save();
        }
        return false;
    }

    public function finalize(): bool
    {
        if ($this->status === 'draft') {
            $this->status = 'final';
            return $this->save();
        }
        return false;
    }
}