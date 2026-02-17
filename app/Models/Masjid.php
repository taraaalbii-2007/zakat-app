<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class Masjid extends Model
{
    use HasFactory;
    
    protected $table = 'masjid';
    
    // Konstanta untuk maksimal foto
    public const MAX_FOTO = 5;
    
    protected $fillable = [
        'uuid',
        
        // DATA ADMIN MASJID - TAMBAHKAN INI
        'admin_nama',
        'admin_telepon',
        'admin_email',
        'admin_foto',
        
        // DATA SEJARAH - TAMBAHKAN INI
        'sejarah',
        'tahun_berdiri',
        'pendiri',
        'kapasitas_jamaah',
        
        // DATA MASJID
        'nama',
        'kode_masjid',
        'alamat',
        'provinsi_kode',
        'kota_kode',
        'kecamatan_kode',
        'kelurahan_kode',
        'provinsi_nama',
        'kota_nama',
        'kecamatan_nama',
        'kelurahan_nama',
        'kode_pos',
        'telepon',
        'email',
        'deskripsi',
        
        // FOTO MASJID
        'foto',
        
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'foto' => 'array',
        'tahun_berdiri' => 'integer', // TAMBAHKAN INI
        'kapasitas_jamaah' => 'integer', // TAMBAHKAN INI
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'alamat_lengkap',
        'foto_urls',
        'foto_count',
        'admin_foto_url', // TAMBAHKAN INI
        'tahun_berdiri_formatted', // TAMBAHKAN INI
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            
            if (empty($model->kode_masjid)) {
                $model->kode_masjid = self::generateKodeMasjid();
            }
            
            $model->syncWilayahNames();
            $model->validateFotoCount();
        });

        static::updating(function ($model) {
            if ($model->isDirty(['provinsi_kode', 'kota_kode', 'kecamatan_kode', 'kelurahan_kode'])) {
                $model->syncWilayahNames();
            }
            $model->validateFotoCount();
        });
        
        static::updated(function ($model) {
            $originalFotos = $model->getOriginal('foto') ?? [];
            $currentFotos = $model->foto ?? [];
            
            $deletedFotos = array_diff($originalFotos, $currentFotos);
            
            foreach ($deletedFotos as $foto) {
                if (Storage::disk('public')->exists($foto)) {
                    Storage::disk('public')->delete($foto);
                }
            }
            
            // TAMBAHKAN INI: Handle hapus foto admin
            $originalAdminFoto = $model->getOriginal('admin_foto');
            $currentAdminFoto = $model->admin_foto;
            
            if ($originalAdminFoto && $originalAdminFoto !== $currentAdminFoto) {
                if (Storage::disk('public')->exists($originalAdminFoto)) {
                    Storage::disk('public')->delete($originalAdminFoto);
                }
            }
        });
        
        static::deleted(function ($model) {
            $fotos = $model->foto ?? [];
            foreach ($fotos as $foto) {
                if (Storage::disk('public')->exists($foto)) {
                    Storage::disk('public')->delete($foto);
                }
            }
            
            // TAMBAHKAN INI: Hapus foto admin
            if ($model->admin_foto && Storage::disk('public')->exists($model->admin_foto)) {
                Storage::disk('public')->delete($model->admin_foto);
            }
        });
    }

    public static function generateKodeMasjid()
    {
        $prefix = 'MSJ';
        $year = date('Y');
        
        $lastMasjid = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastMasjid) {
            $lastNumber = (int) substr($lastMasjid->kode_masjid, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }


    public function syncWilayahNames()
    {
        if ($this->provinsi_kode) {
            $province = Province::where('code', $this->provinsi_kode)->first();
            $this->provinsi_nama = $province ? $province->name : null;
        }

        if ($this->kota_kode) {
            $city = City::where('code', $this->kota_kode)->first();
            $this->kota_nama = $city ? $city->name : null;
        }

        if ($this->kecamatan_kode) {
            $district = District::where('code', $this->kecamatan_kode)->first();
            $this->kecamatan_nama = $district ? $district->name : null;
        }

        if ($this->kelurahan_kode) {
            $village = Village::where('code', $this->kelurahan_kode)->first();
            $this->kelurahan_nama = $village ? $village->name : null;
            
            if ($village && isset($village->meta) && is_array($village->meta)) {
                $meta = $village->meta;
                if (isset($meta['postal_code']) && empty($this->kode_pos)) {
                    $this->kode_pos = $meta['postal_code'];
                }
            }
        }
    }

    protected function validateFotoCount()
    {
        if ($this->foto && count($this->foto) > self::MAX_FOTO) {
            throw new \Exception("Maksimal " . self::MAX_FOTO . " foto yang diizinkan.");
        }
    }

    /**
     * METHOD UNTUK MANAJEMEN FOTO MASJID
     */
    public function addFoto(string $path): bool
    {
        $fotos = $this->foto ?? [];
        
        if (count($fotos) >= self::MAX_FOTO) {
            return false;
        }
        
        $fotos[] = $path;
        $this->foto = $fotos;
        return $this->save();
    }
    
    public function removeFoto(string $path): bool
    {
        $fotos = $this->foto ?? [];
        
        $index = array_search($path, $fotos);
        
        if ($index !== false) {
            unset($fotos[$index]);
            
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            
            $this->foto = array_values($fotos);
            return $this->save();
        }
        
        return false;
    }
    
    public function removeFotoByIndex(int $index): bool
    {
        $fotos = $this->foto ?? [];
        
        if (isset($fotos[$index])) {
            $path = $fotos[$index];
            
            unset($fotos[$index]);
            
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            
            $this->foto = array_values($fotos);
            return $this->save();
        }
        
        return false;
    }
    
    public function clearAllFotos(): bool
    {
        $fotos = $this->foto ?? [];
        
        foreach ($fotos as $foto) {
            if (Storage::disk('public')->exists($foto)) {
                Storage::disk('public')->delete($foto);
            }
        }
        
        $this->foto = [];
        return $this->save();
    }
    
    public function canAddMoreFotos(): bool
    {
        $currentCount = count($this->foto ?? []);
        return $currentCount < self::MAX_FOTO;
    }
    
    public function getRemainingFotoSlots(): int
    {
        $currentCount = count($this->foto ?? []);
        return self::MAX_FOTO - $currentCount;
    }

    /**
     * ATTRIBUTES BARU - TAMBAHKAN INI
     */
    public function getAlamatLengkapAttribute()
    {
        $parts = [];
        
        if ($this->alamat) {
            $parts[] = $this->alamat;
        }
        
        if ($this->kelurahan_nama) {
            $parts[] = 'Kel. ' . $this->kelurahan_nama;
        }
        
        if ($this->kecamatan_nama) {
            $parts[] = 'Kec. ' . $this->kecamatan_nama;
        }
        
        if ($this->kota_nama) {
            $parts[] = $this->kota_nama;
        }
        
        if ($this->provinsi_nama) {
            $parts[] = 'Prov. ' . $this->provinsi_nama;
        }
        
        if ($this->kode_pos) {
            $parts[] = $this->kode_pos;
        }
        
        return implode(', ', $parts);
    }

    public function getFotoUrlsAttribute()
    {
        $urls = [];
        $fotos = $this->foto ?? [];
        
        foreach ($fotos as $foto) {
            $urls[] = asset('storage/' . $foto);
        }
        
        if (empty($urls)) {
            return [asset('images/default-masjid.jpg')];
        }
        
        return $urls;
    }
    
    public function getFotoUtamaUrlAttribute()
    {
        $fotos = $this->foto ?? [];
        
        if (!empty($fotos) && isset($fotos[0])) {
            return asset('storage/' . $fotos[0]);
        }
        
        return asset('images/default-masjid.jpg');
    }
    
    public function getFotoCountAttribute()
    {
        return count($this->foto ?? []);
    }
    
    /**
     * TAMBAHKAN ATTRIBUTE UNTUK FOTO ADMIN
     */
    public function getAdminFotoUrlAttribute()
    {
        if ($this->admin_foto && Storage::disk('public')->exists($this->admin_foto)) {
            return asset('storage/' . $this->admin_foto);
        }
        
        return asset('images/default-admin.jpg');
    }
    
    /**
     * TAMBAHKAN ATTRIBUTE UNTUK TAHUN BERDIRI YANG SUDAH DIFORMAT
     */
    public function getTahunBerdiriFormattedAttribute()
    {
        if ($this->tahun_berdiri) {
            return $this->tahun_berdiri;
        }
        
        return '-';
    }
    
    /**
     * TAMBAHKAN ATTRIBUTE UNTUK USIA MASJID
     */
    public function getUsiaMasjidAttribute()
    {
        if ($this->tahun_berdiri) {
            $currentYear = date('Y');
            return $currentYear - $this->tahun_berdiri;
        }
        
        return null;
    }

    /**
     * RELASI
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'provinsi_kode', 'code');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'kota_kode', 'code');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'kecamatan_kode', 'code');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'kelurahan_kode', 'code');
    }

    /**
     * SCOPES
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('nama', 'like', "%{$term}%")
              ->orWhere('kode_masjid', 'like', "%{$term}%")
              ->orWhere('alamat', 'like', "%{$term}%")
              ->orWhere('admin_nama', 'like', "%{$term}%") // TAMBAHKAN INI
              ->orWhere('pendiri', 'like', "%{$term}%") // TAMBAHKAN INI
              ->orWhere('provinsi_nama', 'like', "%{$term}%")
              ->orWhere('kota_nama', 'like', "%{$term}%")
              ->orWhere('kecamatan_nama', 'like', "%{$term}%")
              ->orWhere('kelurahan_nama', 'like', "%{$term}%");
        });
    }

    public function scopeByProvinsi($query, $provinsiKode)
    {
        return $query->where('provinsi_kode', $provinsiKode);
    }

    public function scopeByKota($query, $kotaKode)
    {
        return $query->where('kota_kode', $kotaKode);
    }
    
    /**
     * TAMBAHKAN SCOPES BARU
     */
    public function scopeByTahunBerdiri($query, $tahun)
    {
        return $query->where('tahun_berdiri', $tahun);
    }
    
    public function scopeBerdiriSebelum($query, $tahun)
    {
        return $query->where('tahun_berdiri', '<=', $tahun);
    }
    
    public function scopeBerdiriSetelah($query, $tahun)
    {
        return $query->where('tahun_berdiri', '>=', $tahun);
    }
}