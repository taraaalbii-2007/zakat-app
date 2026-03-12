<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Bulletin extends Model
{
    use SoftDeletes;

    protected $table = 'bulletins';

    protected $fillable = [
        'uuid',
        'created_by',
        'lembaga_id',
        'kategori_bulletin_id',
        'judul',
        'slug',
        'konten',
        'lokasi',
        'thumbnail',
        'image_caption',
        'published_at',
        'view_count',
        'status',
        'rejection_reason',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'reviewed_at'  => 'datetime',
        'view_count'   => 'integer',
    ];

    // Status constants
    const STATUS_DRAFT    = 'draft';
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

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

            // Default status
            if (empty($model->status)) {
                $model->status = self::STATUS_DRAFT;
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
        $slug     = Str::slug($judul);
        $original = $slug;
        $count    = 1;

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

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class, 'lembaga_id');
    }

    public function kategoriBulletin()
    {
        return $this->belongsTo(KategoriBulletin::class, 'kategori_bulletin_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Pengguna::class, 'reviewed_by');
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
        return $query->where('status', self::STATUS_APPROVED)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeByLembaga($query, $lembagaId)
    {
        return $query->where('lembaga_id', $lembagaId);
    }

    // ============================================
    // ACCESSORS / HELPERS
    // ============================================
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }
        return Storage::url($this->thumbnail);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT    => 'Draft',
            self::STATUS_PENDING  => 'Menunggu Persetujuan',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            default               => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT    => 'gray',
            self::STATUS_PENDING  => 'yellow',
            self::STATUS_APPROVED => 'green',
            self::STATUS_REJECTED => 'red',
            default               => 'gray',
        };
    }

    public function isDraft(): bool    { return $this->status === self::STATUS_DRAFT; }
    public function isPending(): bool  { return $this->status === self::STATUS_PENDING; }
    public function isApproved(): bool { return $this->status === self::STATUS_APPROVED; }
    public function isRejected(): bool { return $this->status === self::STATUS_REJECTED; }

    /**
     * Apakah bulletin bisa diedit (hanya draft atau rejected)
     */
    public function isEditable(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_REJECTED]);
    }
}