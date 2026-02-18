<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DokumentasiPenyaluran extends Model
{
    use HasFactory;

    protected $table = 'dokumentasi_penyaluran';

    protected $fillable = [
        'transaksi_penyaluran_id',
        'path_foto',
        'keterangan_foto',
        'urutan',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenyaluran::class, 'transaksi_penyaluran_id');
    }

    public function getFotoUrlAttribute(): string
    {
        return asset('storage/' . $this->path_foto);
    }
}