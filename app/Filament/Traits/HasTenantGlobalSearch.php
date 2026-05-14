<?php

namespace App\Filament\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait to filter global search results by tenant.
 * Super-admin users can see results from ALL tenants.
 */
trait HasTenantGlobalSearch
{
    public static function getGlobalSearchEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getGlobalSearchEloquentQuery();
        $user = auth()->user();

        if ($user && !$user->hasRole('super_admin')) {
            $query->where('tenant_id', $user->tenant_id);
        }

        return $query;
    }
}
