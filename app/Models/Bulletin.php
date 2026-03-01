<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Bulletin extends Model
{
    use SoftDeletes;

    protected $table = 'bulletins';

    protected $fillable = [
        'uuid',
        'created_by',
        'kategori_bulletin_id',
        'judul',
        'slug',
        'konten',
        'lokasi',
        'thumbnail',
        'image_caption',
        'published_at',
        'view_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'view_count'   => 'integer',
    ];

    // ============================================
    // BOOT
    // ============================================
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }

            if (empty($model->slug)) {
                $model->slug = static::generateSlug($model->judul);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('judul') && empty($model->slug)) {
                $model->slug = static::generateSlug($model->judul);
            }
        });
    }

    // ============================================
    // HELPER - SLUG GENERATOR
    // ============================================
    public static function generateSlug(string $judul): string
    {
        $slug = Str::slug($judul);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================
    public function author()
    {
        return $this->belongsTo(Pengguna::class, 'created_by');
    }

    public function kategoriBulletin()
    {
        return $this->belongsTo(KategoriBulletin::class, 'kategori_bulletin_id');
    }

    // ============================================
    // SCOPES
    // ============================================
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('judul', 'like', "%{$keyword}%")
              ->orWhere('konten', 'like', "%{$keyword}%")
              ->orWhere('lokasi', 'like', "%{$keyword}%");
        });
    }

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_bulletin_id', $kategoriId);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    // ============================================
    // ACCESSORS
    // ============================================
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }

        return \Storage::url($this->thumbnail);
    }
}