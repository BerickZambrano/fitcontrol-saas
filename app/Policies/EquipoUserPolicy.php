<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EquipoUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipoUserPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EquipoUser');
    }

    public function view(AuthUser $authUser, EquipoUser $equipoUser): bool
    {
        return $authUser->can('View:EquipoUser');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EquipoUser');
    }

    public function update(AuthUser $authUser, EquipoUser $equipoUser): bool
    {
        return $authUser->can('Update:EquipoUser');
    }

    public function delete(AuthUser $authUser, EquipoUser $equipoUser): bool
    {
        return $authUser->can('Delete:EquipoUser');
    }

    public function restore(AuthUser $authUser, EquipoUser $equipoUser): bool
    {
        return $authUser->can('Restore:EquipoUser');
    }

    public function forceDelete(AuthUser $authUser, EquipoUser $equipoUser): bool
    {
        return $authUser->can('ForceDelete:EquipoUser');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EquipoUser');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EquipoUser');
    }

    public function replicate(AuthUser $authUser, EquipoUser $equipoUser): bool
    {
        return $authUser->can('Replicate:EquipoUser');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EquipoUser');
    }

}