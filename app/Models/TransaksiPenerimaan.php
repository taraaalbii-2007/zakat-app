<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TransaksiPenerimaan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_penerimaan';

    protected $fillable = [
        'uuid',
        'no_transaksi',
        'tanggal_transaksi',
        'waktu_transaksi',
        'masjid_id',
        'muzakki_id',
        'diinput_muzakki',
        'jenis_zakat_id',
        'tipe_zakat_id',
        'program_zakat_id',
        'muzakki_nama',
        'muzakki_telepon',
        'muzakki_email',
        'muzakki_alamat',
        'muzakki_nik',
        'metode_penerimaan',
        'amil_id',
        'latitude',
        'longitude',
        'status_penjemputan',
        'waktu_request',
        'waktu_diterima_amil',
        'waktu_berangkat',
        'waktu_sampai',
        'waktu_selesai',
        // Nominal
        'jumlah',          // Nominal zakat wajib
        'jumlah_dibayar',  // Nominal aktual yang dibayar (bisa > jumlah)
        'jumlah_infaq',    // Kelebihan bayar = infaq sukarela
        'has_infaq',
        'metode_pembayaran',
        // Konfirmasi manual
        'konfirmasi_status',
        'no_referensi_transfer',
        'dikonfirmasi_oleh',
        'konfirmasi_at',
        'catatan_konfirmasi',
        // Detail Fitrah
        'jumlah_jiwa',
        'nominal_per_jiwa',
        'jumlah_beras_kg',
        'harga_beras_per_kg',
        'nama_jiwa_json',
        // Detail Mal
        'nilai_harta',
        'nisab_saat_ini',
        'sudah_haul',
        'tanggal_mulai_haul',
        // Bukti & catatan
        'no_kwitansi',
        'bukti_transfer',
        'foto_dokumentasi',
        'keterangan',
        // Status
        'status',
        'alasan_penolakan',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'tanggal_transaksi'   => 'date',
        'waktu_transaksi'     => 'datetime',
        'latitude'            => 'decimal:8',
        'longitude'           => 'decimal:8',
        'jumlah'              => 'decimal:2',
        'jumlah_dibayar'      => 'decimal:2',
        'jumlah_infaq'        => 'decimal:2',
        'has_infaq'           => 'boolean',
        'diinput_muzakki'     => 'boolean',
        'jumlah_jiwa'         => 'integer',
        'nama_jiwa_json' => 'array',
        'nominal_per_jiwa'    => 'decimal:2',
        'jumlah_beras_kg'     => 'decimal:2',
        'harga_beras_per_kg'  => 'decimal:2',
        'nilai_harta'         => 'decimal:2',
        'nisab_saat_ini'      => 'decimal:2',
        'sudah_haul'          => 'boolean',
        'tanggal_mulai_haul'  => 'date',
        'foto_dokumentasi'    => 'array',
        'konfirmasi_at'       => 'datetime',
        'waktu_request'       => 'datetime',
        'waktu_diterima_amil' => 'datetime',
        'waktu_berangkat'     => 'datetime',
        'waktu_sampai'        => 'datetime',
        'waktu_selesai'       => 'datetime',
        'verified_at'         => 'datetime',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
    ];

    protected $hidden = ['id'];

    // ===============================
    // BOOT
    // ===============================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid))           $model->uuid = (string) Str::uuid();
            if (empty($model->no_transaksi))   $model->no_transaksi = self::generateNoTransaksi($model->masjid_id);
            if (empty($model->tanggal_transaksi)) $model->tanggal_transaksi = now();
            if (empty($model->waktu_transaksi))   $model->waktu_transaksi = now();
            if (empty($model->no_kwitansi))    $model->no_kwitansi = self::generateNoKwitansi($model->masjid_id);
            // Default jumlah_dibayar = jumlah jika tidak diisi
            if (is_null($model->jumlah_dibayar) && !is_null($model->jumlah)) {
                $model->jumlah_dibayar = $model->jumlah;
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    // ===============================
    // GENERATE NOMOR
    // ===============================

    public static function generateNoTransaksi($masjidId): string
    {
        $masjid     = Masjid::find($masjidId);
        $kodeMasjid = $masjid ? $masjid->kode_masjid : 'MSJ001';
        $year       = date('Y');
        $month      = date('m');

        $last = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $newNumber = $last ? ((int) substr($last->no_transaksi, -4)) + 1 : 1;
        return 'TRX-' . $kodeMasjid . '-' . $year . $month . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public static function generateNoKwitansi($masjidId): string
    {
        $masjid     = Masjid::find($masjidId);
        $kodeMasjid = $masjid ? $masjid->kode_masjid : 'MSJ001';
        $year       = date('Y');

        $last = self::whereYear('created_at', $year)
            ->whereNotNull('no_kwitansi')
            ->orderBy('id', 'desc')
            ->first();

        $newNumber = ($last && $last->no_kwitansi) ? ((int) substr($last->no_kwitansi, -4)) + 1 : 1;
        return 'KWT-' . $kodeMasjid . '-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // ===============================
    // RELATIONSHIPS
    // ===============================

    public function masjid()
    {
        return $this->belongsTo(Masjid::class, 'masjid_id');
    }
    public function muzakki()
    {
        return $this->belongsTo(Muzakki::class, 'muzakki_id');
    }
    public function jenisZakat()
    {
        return $this->belongsTo(JenisZakat::class, 'jenis_zakat_id');
    }
    public function tipeZakat()
    {
        return $this->belongsTo(TipeZakat::class, 'tipe_zakat_id');
    }
    public function programZakat()
    {
        return $this->belongsTo(ProgramZakat::class, 'program_zakat_id');
    }
    public function amil()
    {
        return $this->belongsTo(Amil::class, 'amil_id');
    }
    public function verifiedBy()
    {
        return $this->belongsTo(Pengguna::class, 'verified_by');
    }
    public function dikonfirmasiOleh()
    {
        return $this->belongsTo(Pengguna::class, 'dikonfirmasi_oleh');
    }

    // ===============================
    // SCOPES
    // ===============================

    public function scopeByMasjid($q, $id)
    {
        return $q->where('masjid_id', $id);
    }
    public function scopeByTanggal($q, $tgl)
    {
        return $q->whereDate('tanggal_transaksi', $tgl);
    }
    public function scopeByPeriode($q, $start, $end)
    {
        return $q->whereBetween('tanggal_transaksi', [$start, $end]);
    }
    public function scopeByJenisZakat($q, $id)
    {
        return $q->where('jenis_zakat_id', $id);
    }
    public function scopeByMetodePembayaran($q, $m)
    {
        return $q->where('metode_pembayaran', $m);
    }
    public function scopeByStatus($q, $s)
    {
        return $q->where('status', $s);
    }
    public function scopeVerified($q)
    {
        return $q->where('status', 'verified');
    }
    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }
    public function scopeRejected($q)
    {
        return $q->where('status', 'rejected');
    }
    public function scopeByKonfirmasiStatus($q, $s)
    {
        return $q->where('konfirmasi_status', $s);
    }
    public function scopeMenungguKonfirmasi($q)
    {
        return $q->where('konfirmasi_status', 'menunggu_konfirmasi');
    }
    public function scopeByMetodePenerimaan($q, $m)
    {
        return $q->where('metode_penerimaan', $m);
    }
    public function scopeByAmil($q, $id)
    {
        return $q->where('amil_id', $id);
    }
    public function scopeByStatusPenjemputan($q, $s)
    {
        return $q->where('status_penjemputan', $s);
    }
    public function scopeByMuzakki($q, $id)
    {
        return $q->where('muzakki_id', $id);
    }
    public function scopeDiinputMuzakki($q)
    {
        return $q->where('diinput_muzakki', true);
    }
    public function scopeHasInfaq($q)
    {
        return $q->where('has_infaq', true)->where('jumlah_infaq', '>', 0);
    }

    public function scopeSearch($q, $search)
    {
        if (!$search) return $q;
        return $q->where(function ($q) use ($search) {
            $q->where('no_transaksi', 'like', "%{$search}%")
                ->orWhere('muzakki_nama', 'like', "%{$search}%")
                ->orWhere('muzakki_telepon', 'like', "%{$search}%")
                ->orWhere('no_kwitansi', 'like', "%{$search}%")
                ->orWhere('no_referensi_transfer', 'like', "%{$search}%");
        });
    }

    // ===============================
    // ACCESSORS — BADGES
    // ===============================

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending'  => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Pending</span>',
            'verified' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">Verified</span>',
            'rejected' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 border border-red-200">Rejected</span>',
        ];
        return $badges[$this->status]
            ?? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">' . ucfirst($this->status) . '</span>';
    }

    public function getKonfirmasiStatusBadgeAttribute(): string
    {
        if ($this->metode_pembayaran === 'tunai' || !$this->konfirmasi_status) {
            return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">Tunai</span>';
        }
        $badges = [
            'menunggu_konfirmasi' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Menunggu Konfirmasi</span>',
            'dikonfirmasi'        => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">Dikonfirmasi</span>',
            'ditolak'             => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 border border-red-200">Ditolak</span>',
        ];
        return $badges[$this->konfirmasi_status]
            ?? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">' . ucfirst($this->konfirmasi_status) . '</span>';
    }

    public function getMetodePenerimaanBadgeAttribute(): string
    {
        $badges = [
            'datang_langsung' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">Datang Langsung</span>',
            'dijemput'        => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 border border-purple-200">Dijemput</span>',
            'daring'          => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800 border border-indigo-200">Daring</span>',
        ];
        return $badges[$this->metode_penerimaan]
            ?? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">' . ucfirst($this->metode_penerimaan) . '</span>';
    }

    public function getMetodePembayaranBadgeAttribute(): string
    {
        $badges = [
            'tunai'    => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">Tunai</span>',
            'transfer' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">Transfer</span>',
            'qris'     => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 border border-purple-200">QRIS</span>',
        ];
        return $badges[$this->metode_pembayaran]
            ?? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">' . ucfirst($this->metode_pembayaran) . '</span>';
    }

    public function getStatusPenjemputanBadgeAttribute(): string
    {
        if (!$this->status_penjemputan) return '-';
        $badges = [
            'menunggu'         => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Menunggu</span>',
            'diterima'         => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">Diterima</span>',
            'dalam_perjalanan' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800 border border-indigo-200">Dalam Perjalanan</span>',
            'sampai_lokasi'    => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 border border-purple-200">Sampai Lokasi</span>',
            'selesai'          => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">Selesai</span>',
        ];
        return $badges[$this->status_penjemputan] ?? ucfirst($this->status_penjemputan);
    }

    public function getInfaqBadgeAttribute(): string
    {
        if (!$this->has_infaq || $this->jumlah_infaq <= 0) return '';
        return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-800 border border-amber-200">+ Infaq</span>';
    }

    // ===============================
    // ACCESSORS — FORMAT
    // ===============================

    public function getJumlahFormattedAttribute(): string
    {
        return 'Rp ' . number_format((float)$this->jumlah, 0, ',', '.');
    }

    public function getJumlahDibayarFormattedAttribute(): string
    {
        if (is_null($this->jumlah_dibayar)) return $this->jumlah_formatted;
        return 'Rp ' . number_format((float)$this->jumlah_dibayar, 0, ',', '.');
    }

    public function getJumlahInfaqFormattedAttribute(): string
    {
        return 'Rp ' . number_format((float)($this->jumlah_infaq ?? 0), 0, ',', '.');
    }

    // ===============================
    // ACCESSORS — FLAGS
    // ===============================

    public function getIsNonTunaiAttribute(): bool
    {
        return in_array($this->metode_pembayaran, ['transfer', 'qris']);
    }

    public function getIsTunaiAttribute(): bool
    {
        return $this->metode_pembayaran === 'tunai';
    }

    public function getIsZakatFitrahAttribute(): bool
    {
        return $this->jenisZakat && stripos($this->jenisZakat->nama, 'fitrah') !== false;
    }

    public function getIsZakatMalAttribute(): bool
    {
        return $this->jenisZakat && stripos($this->jenisZakat->nama, 'mal') !== false;
    }

    public function getIsBayarBerasAttribute(): bool
    {
        return $this->tipeZakat && stripos($this->tipeZakat->nama, 'beras') !== false;
    }

    public function getIsDataringAttribute(): bool
    {
        return $this->metode_penerimaan === 'daring';
    }

    public function getIsDijemputAttribute(): bool
    {
        return $this->metode_penerimaan === 'dijemput';
    }

    public function getIsDatangLangsungAttribute(): bool
    {
        return $this->metode_penerimaan === 'datang_langsung';
    }

    // ===============================
    // ACCESSORS — DETAIL TEXT
    // ===============================

    public function getDetailZakatFitrahAttribute(): ?string
    {
        if (!$this->isZakatFitrah) return null;
        if ($this->isBayarBeras) {
            return $this->jumlah_beras_kg . ' kg beras';
        }
        return $this->jumlah_jiwa . ' jiwa × Rp '
            . number_format((float)$this->nominal_per_jiwa, 0, ',', '.')
            . ' = ' . $this->jumlah_formatted;
    }

    public function getDetailZakatMalAttribute(): ?string
    {
        if (!$this->isZakatMal) return null;
        $detail = 'Nilai Harta: Rp ' . number_format((float)$this->nilai_harta, 0, ',', '.');
        if ($this->nisab_saat_ini) $detail .= ' | Nisab: Rp ' . number_format((float)$this->nisab_saat_ini, 0, ',', '.');
        $detail .= ' | Haul: ' . ($this->sudah_haul ? 'Sudah' : 'Belum');
        return $detail;
    }

    public function getDetailPembayaranAttribute(): string
    {
        $detail = $this->jumlah_dibayar_formatted;
        if ($this->has_infaq && $this->jumlah_infaq > 0) {
            $detail .= ' (Zakat: ' . $this->jumlah_formatted . ' + Infaq: ' . $this->jumlah_infaq_formatted . ')';
        }
        return $detail;
    }

    // ===============================
    // LOGIC HELPERS
    // ===============================

    public function getIsPembayaranDikonfirmasiAttribute(): bool
    {
        if ($this->metode_pembayaran === 'tunai') return true;
        return $this->konfirmasi_status === 'dikonfirmasi';
    }

    public function getBisaDiverifikasiAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getBisaDitolakAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getBisaDikonfirmasiAttribute(): bool
    {
        return in_array($this->metode_pembayaran, ['transfer', 'qris'])
            && $this->konfirmasi_status === 'menunggu_konfirmasi';
    }

    public function getBisaDiupdatePenjemputanAttribute(): bool
    {
        return $this->metode_penerimaan === 'dijemput'
            && in_array($this->status_penjemputan, ['menunggu', 'diterima', 'dalam_perjalanan', 'sampai_lokasi']);
    }

    public function getIsLengkapAttribute(): bool
    {
        if ($this->metode_penerimaan === 'dijemput') {
            return !is_null($this->jenis_zakat_id)
                && !is_null($this->metode_pembayaran)
                && !is_null($this->jumlah)
                && ($this->jumlah > 0 || $this->isBayarBeras);
        }
        return true;
    }

    public function getIsDraftAttribute(): bool
    {
        return $this->metode_penerimaan === 'dijemput'
            && (is_null($this->jenis_zakat_id) || is_null($this->metode_pembayaran));
    }

    /**
     * Apakah ada kelebihan bayar yang jadi infaq?
     */
    public function getAdaInfaqAttribute(): bool
    {
        return $this->has_infaq && ($this->jumlah_infaq ?? 0) > 0;
    }

    /**
     * Kekurangan bayar (jika dibayar kurang dari zakat)
     */
    public function getKekuranganBayarAttribute(): float
    {
        if (is_null($this->jumlah_dibayar)) return 0;
        return max(0, (float)$this->jumlah - (float)$this->jumlah_dibayar);
    }
    public function scopeByTahun($query, $tahun)
    {
        return $query->whereYear('tanggal_transaksi', $tahun);
    }
}   
