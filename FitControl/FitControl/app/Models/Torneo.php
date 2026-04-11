<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Torneo extends Model
{
    use Searchable;

    protected $fillable = [
        'nombre',
        'categoria',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'tenant_id',
    ];

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'nombre' => $this->nombre,
            'categoria' => $this->categoria,
            'estado' => $this->estado,
            'tenant_id' => $this->tenant_id,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return true;
    }

    public function getScoutKey(): mixed
    {
        return $this->getKey();
    }

    public function getScoutKeyName(): mixed
    {
        return $this->getKeyName();
    }

    protected static function booted()
    {
        // Asignar tenant automáticamente
        static::creating(function ($model) {
            if (auth()->check() && empty($model->tenant_id)) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });

        // Scope global por tenant (super_admin ve todo)
        static::addGlobalScope('tenant', function ($query) {
            if (auth()->check()) {
                $userId = auth()->id();
                $isSuperAdmin = Cache::remember("user_roles_{$userId}", 300, function () use ($userId) {
                    $roles = DB::table('model_has_roles')
                        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('model_has_roles.model_id', $userId)
                        ->pluck('name')
                        ->toArray();
                    return in_array('super_admin', $roles);
                });

                if (!$isSuperAdmin) {
                    $query->where('tenant_id', auth()->user()->tenant_id);
                }
            }
        });
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class);
    }
}
