<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Rendimiento;
use Illuminate\Auth\Access\HandlesAuthorization;

class RendimientoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Rendimiento');
    }

    public function view(AuthUser $authUser, Rendimiento $rendimiento): bool
    {
        return $authUser->can('View:Rendimiento');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Rendimiento');
    }

    public function update(AuthUser $authUser, Rendimiento $rendimiento): bool
    {
        return $authUser->can('Update:Rendimiento');
    }

    public function delete(AuthUser $authUser, Rendimiento $rendimiento): bool
    {
        return $authUser->can('Delete:Rendimiento');
    }

    public function restore(AuthUser $authUser, Rendimiento $rendimiento): bool
    {
        return $authUser->can('Restore:Rendimiento');
    }

    public function forceDelete(AuthUser $authUser, Rendimiento $rendimiento): bool
    {
        return $authUser->can('ForceDelete:Rendimiento');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Rendimiento');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Rendimiento');
    }

    public function replicate(AuthUser $authUser, Rendimiento $rendimiento): bool
    {
        return $authUser->can('Replicate:Rendimiento');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Rendimiento');
    }

}