<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Traits\Loggable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;
    use Loggable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengguna';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'peran',
        'lembaga_id', // ✅ TAMBAHKAN INI
        'username',
        'email',
        'email_verified_at',
        'password',
        'google_id',
        'google_token',
        'refresh_token',
        'is_active',
        'verification_token',
        'verification_token_expires_at',
        'password_reset_token',
        'password_reset_token_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_token',
        'refresh_token',
        'verification_token',
        'password_reset_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'verification_token_expires_at' => 'datetime',
        'password_reset_token_expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($pengguna) {
            if (empty($pengguna->uuid)) {
                $pengguna->uuid = (string) Str::uuid();
            }
        });
    }

    // ===============================
    // RELATIONSHIPS
    // ===============================

    /**
     * Relasi ke Lembaga sebagai Admin (untuk admin_lembaga)
     */
    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class, 'lembaga_id');
    }

    /**
     * Relasi ke tabel Lembaga sebagai Amil
     */
    public function lembagaAsAmil()
    {
        return $this->belongsTo(Lembaga::class, 'lembaga_id');
    }

    /**
     * Relasi ke tabel Amil (jika user adalah amil)
     */
    public function amil()
    {
        return $this->hasOne(Amil::class, 'pengguna_id');
    }

    // ===============================
    // ACCESSORS & MUTATORS
    // ===============================

    /**
     * Get the user's display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->username ?? $this->email;
    }

    /**
     * Get nama lengkap (alias untuk display_name untuk kompatibilitas)
     */
    public function getNamaLengkapAttribute(): string
    {
        return $this->display_name;
    }

    /**
     * Get nama (alias untuk username untuk kompatibilitas) 
     */
    public function getNamaAttribute(): string
    {
        return $this->username ?? $this->email ?? '';
    }

    /**
     * Get the user's role name in Indonesian
     */
    public function getRoleNameAttribute(): string
    {
        return match ($this->peran) {
            'superadmin' => 'Super Admin',
            'admin_lembaga' => 'Admin Lembaga',
            'amil' => 'Amil',
            default => 'Unknown'
        };
    }

    /**
     * Check if email is verified
     */
    public function getIsEmailVerifiedAttribute(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Check if user registered via Google
     */
    public function getIsGoogleUserAttribute(): bool
    {
        return !is_null($this->google_id);
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'Aktif' : 'Tidak Aktif';
    }

    /**
     * Get status badge color for UI
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->is_active ? 'success' : 'danger';
    }

    /**
     * Get lembaga name (if any)
     */
    public function getLembagaNameAttribute(): ?string
    {
        if (!$this->lembaga) {
            return null;
        }
        return $this->lembaga->nama;
    }

    // ===============================
    // QUERY SCOPES
    // ===============================

    /**
     * Scope untuk filter berdasarkan peran
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('peran', $role);
    }

    /**
     * Scope untuk pengguna yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk pengguna yang tidak aktif
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope untuk pengguna dengan email terverifikasi
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope untuk pengguna dengan email belum terverifikasi
     */
    public function scopeUnverified($query)
    {
        return $query->whereNull('email_verified_at');
    }

    /**
     * Scope untuk pengguna Google
     */
    public function scopeGoogleUsers($query)
    {
        return $query->whereNotNull('google_id');
    }

    /**
     * Scope untuk pengguna non-Google
     */
    public function scopeNonGoogleUsers($query)
    {
        return $query->whereNull('google_id');
    }

    /**
     * Scope untuk superadmin
     */
    public function scopeSuperadmins($query)
    {
        return $query->where('peran', 'superadmin');
    }

    /**
     * Scope untuk admin lembaga
     */
    public function scopeAdminLembagas($query)
    {
        return $query->where('peran', 'admin_lembaga');
    }

    /**
     * Scope untuk amil
     */
    public function scopeAmils($query)
    {
        return $query->where('peran', 'amil');
    }

    /**
     * Scope untuk pengguna dengan lembaga tertentu
     */
    public function scopeByLembaga($query, $lembagaId)
    {
        return $query->where('lembaga_id', $lembagaId);
    }

    /**
     * Scope untuk search berdasarkan username atau email
     */
    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('username', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // ===============================
    // HELPER METHODS
    // ===============================

    /**
     * Check if user is superadmin
     */
    public function isSuperadmin(): bool
    {
        return $this->peran === 'superadmin';
    }

    /**
     * Check if user is admin lembaga
     */
    public function isAdminLembaga(): bool
    {
        return $this->peran === 'admin_lembaga';
    }

    /**
     * Check if user is amil
     */
    public function isAmil(): bool
    {
        return $this->peran === 'amil';
    }

    public function isMuzakki(): bool
    {
        return $this->peran === 'muzakki';
    }

    /**
     * Check if user has lembaga
     */
    public function hasLembaga(): bool
    {
        return !is_null($this->lembaga_id);
    }


    /**
     * Get lembaga for current role
     */
    public function getCurrentLembaga()
    {
        if ($this->isAdminLembaga()) {
            return $this->lembaga;
        } elseif ($this->isAmil() && $this->amil) {
            return $this->amil->lembaga;
        }
        return null;
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Check if verification token is valid
     */
    public function isVerificationTokenValid(?string $token): bool
    {
        if (!$token || !$this->verification_token) {
            return false;
        }

        if ($this->verification_token !== $token) {
            return false;
        }

        if ($this->verification_token_expires_at && now()->isAfter($this->verification_token_expires_at)) {
            return false;
        }

        return true;
    }

    /**
     * Check if password reset token is valid
     */
    public function isPasswordResetTokenValid(?string $token): bool
    {
        if (!$token || !$this->password_reset_token) {
            return false;
        }

        if ($this->password_reset_token !== $token) {
            return false;
        }

        if ($this->password_reset_token_expires_at && now()->isAfter($this->password_reset_token_expires_at)) {
            return false;
        }

        return true;
    }

    /**
     * Generate verification token
     */
    public function generateVerificationToken(int $expiryMinutes = 60): string
    {
        $token = Str::random(64);

        $this->forceFill([
            'verification_token' => $token,
            'verification_token_expires_at' => now()->addMinutes($expiryMinutes),
        ])->save();

        return $token;
    }

    /**
     * Generate password reset token
     */
    public function generatePasswordResetToken(int $expiryMinutes = 60): string
    {
        $token = Str::random(64);

        $this->forceFill([
            'password_reset_token' => $token,
            'password_reset_token_expires_at' => now()->addMinutes($expiryMinutes),
        ])->save();

        return $token;
    }

    /**
     * Clear verification token
     */
    public function clearVerificationToken(): bool
    {
        return $this->forceFill([
            'verification_token' => null,
            'verification_token_expires_at' => null,
        ])->save();
    }

    /**
     * Clear password reset token
     */
    public function clearPasswordResetToken(): bool
    {
        return $this->forceFill([
            'password_reset_token' => null,
            'password_reset_token_expires_at' => null,
        ])->save();
    }

    /**
     * Activate user account
     */
    public function activate(): bool
    {
        return $this->forceFill([
            'is_active' => true,
        ])->save();
    }

    /**
     * Deactivate user account
     */
    public function deactivate(): bool
    {
        return $this->forceFill([
            'is_active' => false,
        ])->save();
    }

    /**
     * Update Google tokens
     */
    public function updateGoogleTokens(string $accessToken, ?string $refreshToken = null): bool
    {
        $data = ['google_token' => $accessToken];

        if ($refreshToken) {
            $data['refresh_token'] = $refreshToken;
        }

        return $this->forceFill($data)->save();
    }

    /**
     * Assign lembaga to user
     */
    public function assignLembaga($lembagaId): bool
    {
        return $this->forceFill([
            'lembaga_id' => $lembagaId,
        ])->save();
    }

    /**
     * Remove lembaga assignment
     */
    public function removeLembaga(): bool
    {
        return $this->forceFill([
            'lembaga_id' => null,
        ])->save();
    }

    /**
     * Get the route key name for Laravel routing
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // Di app/Models/Pengguna.php — tambahkan method ini
    public function muzakki()
    {
        return $this->hasOne(\App\Models\Muzakki::class, 'pengguna_id');
    }
}