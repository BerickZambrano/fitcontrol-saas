<?php

namespace App\Models\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait BelongsToTenant
{
    protected static function booted()
    {
        // 🌍 FILTRO GLOBAL POR TENANT (el super_admin ve todo)
        static::addGlobalScope('tenant', function (Builder $query) {
            $userId = auth()->id();
            
            if ($userId) {
                // Obtener info del usuario directamente de BD para evitar bucle infinito
                $userInfo = Cache::remember("user_info_{$userId}", 300, function () use ($userId) {
                    return DB::table('users')->where('id', $userId)->first(['id', 'tenant_id']);
                });
                
                if ($userInfo) {
                    $isSuperAdmin = Cache::remember("user_roles_{$userId}", 300, function () use ($userId) {
                        $roles = DB::table('model_has_roles')
                            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                            ->where('model_has_roles.model_id', $userId)
                            ->pluck('name')
                            ->toArray();
                        return in_array('super_admin', $roles);
                    });
                    
                    if (!$isSuperAdmin) {
                        $query->where('tenant_id', $userInfo->tenant_id);
                    }
                }
            }
        });

        // 🧩 ASIGNAR tenant_id SIEMPRE AL CREAR (INCLUSO super_admin)
        static::creating(function ($model) {
            // Si ya tiene tenant_id definido (ej. por mutateFormDataBeforeCreate), no sobrescribir
            if (!empty($model->tenant_id)) {
                return;
            }

            $userId = auth()->id();

            if ($userId) {
                $userInfo = Cache::remember("user_info_{$userId}", 300, function () use ($userId) {
                    return DB::table('users')->where('id', $userId)->first(['id', 'tenant_id']);
                });

                if ($userInfo) {
                    $isSuperAdmin = Cache::remember("user_roles_{$userId}", 300, function () use ($userId) {
                        $roles = DB::table('model_has_roles')
                            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                            ->where('model_has_roles.model_id', $userId)
                            ->pluck('name')
                            ->toArray();
                        return in_array('super_admin', $roles);
                    });

                    // Solo asignar tenant_id automáticamente si NO es super_admin
                    if (!$isSuperAdmin && empty($model->tenant_id)) {
                        $model->tenant_id = $userInfo->tenant_id;
                    }
                }
            }
        });
    }

    // 🔗 RELACIÓN CON TENANT
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    
    // 🔥 LIMPIAR CACHE cuando se actualiza el usuario
    public static function clearUserInfoCache($userId)
    {
        Cache::forget("user_info_{$userId}");
        Cache::forget("user_roles_{$userId}");
    }
}
