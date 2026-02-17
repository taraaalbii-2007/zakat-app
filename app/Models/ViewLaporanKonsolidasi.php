<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ViewLaporanKonsolidasi extends Model
{
    use HasFactory;

    protected $table = 'view_laporan_konsolidasi';

    protected $fillable = [
        'uuid',
        'masjid_id',
        'tahun',
        'bulan',
        'total_penerimaan',
        'total_penyaluran',
        'saldo_akhir',
        'jumlah_muzakki',
        'jumlah_mustahik',
    ];

    protected $casts = [
        'total_penerimaan' => 'decimal:2',
        'total_penyaluran' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
        'jumlah_muzakki' => 'integer',
        'jumlah_mustahik' => 'integer',
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
    public function getBulanNamaAttribute()
    {
        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $bulanIndonesia[$this->bulan] ?? '';
    }

    public function getPeriodeAttribute()
    {
        return $this->bulan_nama . ' ' . $this->tahun;
    }

    public function getTotalPenerimaanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_penerimaan, 0, ',', '.');
    }

    public function getTotalPenyaluranFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_penyaluran, 0, ',', '.');
    }

    public function getSaldoAkhirFormattedAttribute()
    {
        return 'Rp ' . number_format($this->saldo_akhir, 0, ',', '.');
    }

    // Scopes
    public function scopeByMasjid($query, $masjidId)
    {
        return $query->where('masjid_id', $masjidId);
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    public function scopeByBulan($query, $bulan)
    {
        return $query->where('bulan', $bulan);
    }

    public function scopeByPeriode($query, $tahun, $bulan)
    {
        return $query->where('tahun', $tahun)->where('bulan', $bulan);
    }

    public function scopeOrderByPeriode($query, $direction = 'desc')
    {
        return $query->orderBy('tahun', $direction)->orderBy('bulan', $direction);
    }

    // Helper Methods
    public static function getLast12Months($masjidId)
    {
        return self::byMasjid($masjidId)
            ->orderByPeriode('desc')
            ->limit(12)
            ->get();
    }

    public static function getByYear($masjidId, $tahun)
    {
        return self::byMasjid($masjidId)
            ->byTahun($tahun)
            ->orderBy('bulan', 'asc')
            ->get();
    }
}