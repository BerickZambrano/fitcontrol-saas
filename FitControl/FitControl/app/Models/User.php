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

    /**
     * Get the initials of the user's name.
     */
    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->map(fn (string $name) => mb_substr($name, 0, 1))
            ->take(2)
            ->join('');
    }
}
