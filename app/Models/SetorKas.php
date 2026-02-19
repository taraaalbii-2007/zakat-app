<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SetorKas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'setor_kas';

    protected $fillable = [
        'uuid',
        'no_setor',
        'tanggal_setor',
        'periode_dari',
        'periode_sampai',
        'amil_id',
        'masjid_id',
        'diterima_oleh',
        'jumlah_disetor',
        'jumlah_dari_datang_langsung',
        'jumlah_dari_dijemput',
        'bukti_foto',
        'tanda_tangan_amil',
        'tanda_tangan_penerima',
        'keterangan',
        'status',
        'alasan_penolakan',
        'diterima_at',
        'ditolak_at',
        'jumlah_dihitung_fisik',
    ];

    protected $casts = [
        'tanggal_setor'               => 'date',
        'periode_dari'                => 'date',
        'periode_sampai'              => 'date',
        'jumlah_disetor'              => 'decimal:2',
        'jumlah_dari_datang_langsung' => 'decimal:2',
        'jumlah_dari_dijemput'        => 'decimal:2',
        'jumlah_dihitung_fisik'       => 'decimal:2',
        'diterima_at'                 => 'datetime',
        'ditolak_at'                  => 'datetime',
        'created_at'                  => 'datetime',
        'updated_at'                  => 'datetime',
    ];

    // ============================================
    // BOOT — UUID & No Setor Auto Generate
    // ============================================
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->no_setor)) {
                $model->no_setor = static::generateNoSetor($model->amil_id, $model->masjid_id);
            }
        });

        static::updated(function ($model) {
            // Hapus bukti foto lama jika diganti
            $originalFoto = $model->getOriginal('bukti_foto');
            if ($originalFoto && $originalFoto !== $model->bukti_foto) {
                if (Storage::disk('public')->exists($originalFoto)) {
                    Storage::disk('public')->delete($originalFoto);
                }
            }
        });

        static::deleted(function ($model) {
            foreach (['bukti_foto', 'tanda_tangan_amil', 'tanda_tangan_penerima'] as $field) {
                if ($model->$field && Storage::disk('public')->exists($model->$field)) {
                    Storage::disk('public')->delete($model->$field);
                }
            }
        });
    }

    public static function generateNoSetor(int $amilId, int $masjidId): string
    {
        $masjid     = Masjid::find($masjidId);
        $kodeMasjid = $masjid ? $masjid->kode_masjid : 'MSJ0001';
        $year       = date('Y');
        $month      = date('m');

        $last = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('masjid_id', $masjidId)
            ->orderBy('id', 'desc')
            ->first();

        $seq = $last ? ((int) substr($last->no_setor, -4)) + 1 : 1;

        return 'SETOR-' . $kodeMasjid . '-' . $year . $month . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
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
    public function amil()
    {
        return $this->belongsTo(Amil::class, 'amil_id');
    }

    public function masjid()
    {
        return $this->belongsTo(Masjid::class, 'masjid_id');
    }

    public function penerimaSetoran()
    {
        return $this->belongsTo(Pengguna::class, 'diterima_oleh');
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

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDiterima($query)
    {
        return $query->where('status', 'diterima');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    public function scopeByPeriode($query, string $dari, string $sampai)
    {
        return $query->whereBetween('tanggal_setor', [$dari, $sampai]);
    }

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) return $query;
        return $query->where(function ($q) use ($search) {
            $q->where('no_setor', 'like', "%{$search}%");
        });
    }

    // ============================================
    // ACCESSORS — BADGES
    // ============================================
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'  => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">Pending</span>',
            'diterima' => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">Diterima</span>',
            'ditolak'  => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Ditolak</span>',
            default    => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">-</span>',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'yellow',
            'diterima' => 'green',
            'ditolak'  => 'red',
            default    => 'gray',
        };
    }

    // ============================================
    // ACCESSORS — FORMAT
    // ============================================
    public function getJumlahDisetorFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_disetor, 0, ',', '.');
    }

    public function getJumlahDariDatangLangsungFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_dari_datang_langsung, 0, ',', '.');
    }

    public function getJumlahDariDijemputFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_dari_dijemput, 0, ',', '.');
    }

    public function getJumlahDihitungFisikFormattedAttribute(): string
    {
        if (is_null($this->jumlah_dihitung_fisik)) return '-';
        return 'Rp ' . number_format($this->jumlah_dihitung_fisik, 0, ',', '.');
    }

    public function getPeriodeFormattedAttribute(): string
    {
        return $this->periode_dari->format('d M Y') . ' — ' . $this->periode_sampai->format('d M Y');
    }

    public function getBuktiFotoUrlAttribute(): ?string
    {
        if ($this->bukti_foto && Storage::disk('public')->exists($this->bukti_foto)) {
            return asset('storage/' . $this->bukti_foto);
        }
        return null;
    }

    public function getTandaTanganAmilUrlAttribute(): ?string
    {
        if ($this->tanda_tangan_amil && Storage::disk('public')->exists($this->tanda_tangan_amil)) {
            return asset('storage/' . $this->tanda_tangan_amil);
        }
        return null;
    }

    public function getTandaTanganPenerimaUrlAttribute(): ?string
    {
        if ($this->tanda_tangan_penerima && Storage::disk('public')->exists($this->tanda_tangan_penerima)) {
            return asset('storage/' . $this->tanda_tangan_penerima);
        }
        return null;
    }

    // ============================================
    // LOGIC HELPERS
    // ============================================
    public function getBisaDieditAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getBisaDihapusAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getBisaDiterimaAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getSelisihJumlahAttribute(): ?float
    {
        if (is_null($this->jumlah_dihitung_fisik)) return null;
        return (float) $this->jumlah_dihitung_fisik - (float) $this->jumlah_disetor;
    }
}