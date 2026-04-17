<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles, Searchable;

    /**
     * The "modelWasBooted" callback to register listeners for Spatie role events.
     */
    protected static function booted(): void
    {
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
     * Bootstrap the model and its event listeners.
     */
    protected static function boot(): void
    {
        parent::boot();

        // Clear tenant/role cache when user data changes to prevent stale scopes
        static::saved(function (User $user) {
            static::clearUserInfoCache($user->id);
        });

        static::deleted(function (User $user) {
            static::clearUserInfoCache($user->id);
        });
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

    /**
     * Generate a new 2FA OTP code.
     */
    public function generateTwoFactorOtp(): string
    {
        $this->two_factor_otp = sprintf("%06d", mt_rand(1, 999999));
        $this->two_factor_otp_expires_at = now()->addMinutes(15);
        $this->save();

        return $this->two_factor_otp;
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
}
