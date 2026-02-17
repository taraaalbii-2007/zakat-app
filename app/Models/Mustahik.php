<?php
// app/Models/Mustahik.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Mustahik extends Model
{
    protected $table = 'mustahik';

    protected $fillable = [
        'uuid',
        'masjid_id',
        'kategori_mustahik_id',
        'no_registrasi',
        'nik',
        'kk',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'telepon',
        'alamat',
        'provinsi_kode',
        'kota_kode',
        'kecamatan_kode',
        'kelurahan_kode',
        'rt_rw',
        'kode_pos',
        'pekerjaan',
        'penghasilan_perbulan',
        'jumlah_tanggungan',
        'status_rumah',
        'kondisi_kesehatan',
        'catatan',
        'foto_ktp',
        'foto_kk',
        'foto_rumah',
        'dokumen_lainnya',
        'status_verifikasi',
        'alasan_penolakan',
        'verified_by',
        'verified_at',
        'is_active',
        'tanggal_registrasi',
        'tanggal_nonaktif',
        'created_by',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_registrasi' => 'date',
        'tanggal_nonaktif' => 'date',
        'verified_at' => 'datetime',
        'dokumen_lainnya' => 'array',
        'penghasilan_perbulan' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    // Relasi
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function kategoriMustahik()
    {
        return $this->belongsTo(KategoriMustahik::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Pengguna::class, 'verified_by');
    }

    public function creator()
    {
        return $this->belongsTo(Pengguna::class, 'created_by');
    }

    // Scopes
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('nama_lengkap', 'like', "%{$term}%")
                ->orWhere('nik', 'like', "%{$term}%")
                ->orWhere('no_registrasi', 'like', "%{$term}%")
                ->orWhere('telepon', 'like', "%{$term}%");
        });
    }

    public function scopeByMasjid($query, $masjidId)
    {
        return $query->where('masjid_id', $masjidId);
    }

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_mustahik_id', $kategoriId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status_verifikasi', $status);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>',
            'verified' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Verified</span>',
            'rejected' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>',
        ];

        return $badges[$this->status_verifikasi] ?? '';
    }

    public function getActiveBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Aktif</span>'
            : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Nonaktif</span>';
    }

    public function getGenderLabelAttribute()
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getStatusRumahLabelAttribute()
    {
        $labels = [
            'milik_sendiri' => 'Milik Sendiri',
            'kontrak' => 'Kontrak',
            'menumpang' => 'Menumpang',
            'lainnya' => 'Lainnya',
        ];

        return $labels[$this->status_rumah] ?? '-';
    }

    // Helper Methods
    public function removeDocument($field)
    {
        if ($this->$field && Storage::disk('public')->exists($this->$field)) {
            Storage::disk('public')->delete($this->$field);
        }
    }

    public function removeDokumenLainnyaByIndex($index)
    {
        $dokumen = $this->dokumen_lainnya ?? [];
        
        if (isset($dokumen[$index])) {
            if (Storage::disk('public')->exists($dokumen[$index])) {
                Storage::disk('public')->delete($dokumen[$index]);
            }
            
            unset($dokumen[$index]);
            $this->dokumen_lainnya = array_values($dokumen); // Reset array keys
            $this->save();
            
            return true;
        }
        
        return false;
    }

    public function clearAllDocuments()
    {
        $this->removeDocument('foto_ktp');
        $this->removeDocument('foto_kk');
        $this->removeDocument('foto_rumah');

        if ($this->dokumen_lainnya) {
            foreach ($this->dokumen_lainnya as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
    }

    public function verify($userId)
    {
        $this->update([
            'status_verifikasi' => 'verified',
            'verified_by' => $userId,
            'verified_at' => now(),
            'is_active' => true,
        ]);
    }

    public function reject($reason, $userId)
    {
        $this->update([
            'status_verifikasi' => 'rejected',
            'alasan_penolakan' => $reason,
            'verified_by' => $userId,
            'verified_at' => now(),
            'is_active' => false,
        ]);
    }

    public function activate()
    {
        $this->update([
            'is_active' => true,
            'tanggal_nonaktif' => null,
        ]);
    }

    public function deactivate()
    {
        $this->update([
            'is_active' => false,
            'tanggal_nonaktif' => now(),
        ]);
    }

    // PERMISSION METHODS
    public function canBeEditedBy($userId, $userRole)
    {
        if ($userRole === 'admin_masjid') {
            return true;
        }

        if ($userRole === 'amil') {
            // Amil hanya bisa edit data yang dibuatnya atau data dengan status pending
            return $this->created_by == $userId || $this->status_verifikasi === 'pending';
        }

        return false;
    }

    public function canBeDeletedBy($userId, $userRole)
    {
        if ($userRole === 'admin_masjid') {
            return true;
        }

        if ($userRole === 'amil') {
            // Amil hanya bisa hapus data yang dibuatnya atau data dengan status pending
            return $this->created_by == $userId || $this->status_verifikasi === 'pending';
        }

        return false;
    }

    public function canBeVerifiedBy($userRole)
    {
        return $userRole === 'admin_masjid' && $this->status_verifikasi === 'pending';
    }

    public function canBeRejectedBy($userRole)
    {
        return $userRole === 'admin_masjid' && $this->status_verifikasi === 'pending';
    }

    public function canBeToggledActiveBy($userRole)
    {
        return $userRole === 'admin_masjid';
    }

    // Untuk dropdown actions di index
    public function getActionsAttribute()
    {
        $user = auth()->user();
        $userId = $user->id;
        $userRole = $user->peran;
        
        return [
            'can_view' => true,
            'can_edit' => $this->canBeEditedBy($userId, $userRole),
            'can_delete' => $this->canBeDeletedBy($userId, $userRole),
            'can_verify' => $this->canBeVerifiedBy($userRole),
            'can_reject' => $this->canBeRejectedBy($userRole),
            'can_toggle_active' => $this->canBeToggledActiveBy($userRole),
        ];
    }
}