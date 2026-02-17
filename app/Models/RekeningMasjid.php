<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class RekeningMasjid extends Model
{
    use HasFactory;

    protected $table = 'rekening_masjid';

    protected $fillable = [
        'uuid',
        'masjid_id',
        'nama_bank',
        'nomor_rekening',
        'nama_pemilik',
        'is_primary',
        'is_active',
        'keterangan'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = \Illuminate\Support\Str::uuid();
        });
    }

    /**
     * Relationship with Masjid
     */
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    /**
     * Scope for active accounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for primary account
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope for masjid
     */
    public function scopeByMasjid($query, $masjidId)
    {
        return $query->where('masjid_id', $masjidId);
    }

    /**
     * Accessor for status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->is_active) {
            return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Aktif
            </span>';
        }

        return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Nonaktif
        </span>';
    }

    /**
     * Accessor for primary badge
     */
    public function getPrimaryBadgeAttribute(): string
    {
        if ($this->is_primary) {
            return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Utama
            </span>';
        }

        return '';
    }

    /**
     * Check user permissions for actions
     */
    public function getActionsAttribute(): array
    {
        $user = auth()->user();
        
        return [
            'can_view' => true,
            'can_edit' => true,
            'can_delete' => true,
            'can_toggle_active' => true,
            'can_set_primary' => true,
        ];
    }

    /**
     * Get display name with bank info
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->nama_bank . ' - ' . $this->nomor_rekening;
    }
}