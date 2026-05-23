<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements HasAvatar, FilamentUser
{
    use Notifiable, HasRoles, Searchable, SoftDeletes;

    /**
     * Bootstrap the model and its event listeners.
     * Consolidates all model events in one place.
     */
    protected static function booted(): void
    {
        // Clear cache when user data changes to prevent stale tenant/role scopes
        static::saved(function (User $user) {
            static::clearUserInfoCache($user->id);
        });

        static::deleted(function (User $user) {
            static::clearUserInfoCache($user->id);
        });

        // Clear cache when roles are modified via Spatie Permission
        static::registerModelEvent('roleAttached', function (User $user) {
            static::clearUserInfoCache($user->id);
        });

        static::registerModelEvent('roleDetached', function (User $user) {
            static::clearUserInfoCache($user->id);
        });
    }

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'avatar_url',
        'password',
        'two_factor_type',
        'two_factor_otp',
        'two_factor_otp_expires_at',
    ];

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->roles->pluck('name')->join(', '),
            'tenant_id' => $this->tenant_id,
        ];
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return true;
    }

    /**
     * Get the value used to index the model in Algolia.
     */
    public function getScoutKey(): mixed
    {
        return $this->getKey();
    }

    /**
     * Get the key name used to index the model.
     */
    public function getScoutKeyName(): mixed
    {
        return $this->getKeyName();
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_otp_expires_at' => 'datetime',
        ];
    }



    /**
     * Clear cached user info and roles to force fresh lookups.
     */
    public static function clearUserInfoCache(int $userId): void
    {
        \Illuminate\Support\Facades\Cache::forget("user_info_{$userId}");
        \Illuminate\Support\Facades\Cache::forget("user_roles_{$userId}");
    }

    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->map(fn (string $name) => mb_substr($name, 0, 1))
            ->take(2)
            ->join('');
    }

    public function getProfilePhotoUrlAttribute()
    {
        if (empty($this->avatar_url)) {
            return null;
        }

        return asset('storage/' . $this->avatar_url);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->profile_photo_url;
    }

    /**
     * Generate a new 2FA OTP code.
     *
     * Uses random_int() for cryptographic security.
     * The plain OTP is returned to be sent to the user;
     * only the bcrypt hash is stored in the database.
     */
    public function generateTwoFactorOtp(): string
    {
        $plainOtp = sprintf("%06d", random_int(0, 999999));

        $this->two_factor_otp            = bcrypt($plainOtp);
        $this->two_factor_otp_expires_at = now()->addMinutes(15);
        $this->save();

        return $plainOtp; // devolver texto plano para enviarlo al usuario
    }

    /**
     * Reset the 2FA OTP code.
     */
    public function resetTwoFactorOtp(): void
    {
        $this->two_factor_otp = null;
        $this->two_factor_otp_expires_at = null;
        $this->save();
    }

    /**
     * Check if 2FA is enabled for the user.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_type !== 'none';
    }

    public function jugadorPerfil()
    {
        return $this->hasOne(JugadorPerfil::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->hasRole(['Administrador', 'super_admin'])) {
            return true;
        }

        if ($panel->getId() === 'entrenador') {
            return $this->hasRole('Entrenador');
        }

        if ($panel->getId() === 'jugador') {
            return $this->hasRole('Jugador');
        }

        return false;
    }
}
