<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Equipo;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Equipo');
    }

    public function view(AuthUser $authUser, Equipo $equipo): bool
    {
        return $authUser->can('View:Equipo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Equipo');
    }

    public function update(AuthUser $authUser, Equipo $equipo): bool
    {
        return $authUser->can('Update:Equipo');
    }

    public function delete(AuthUser $authUser, Equipo $equipo): bool
    {
        return $authUser->can('Delete:Equipo');
    }

    public function restore(AuthUser $authUser, Equipo $equipo): bool
    {
        return $authUser->can('Restore:Equipo');
    }

    public function forceDelete(AuthUser $authUser, Equipo $equipo): bool
    {
        return $authUser->can('ForceDelete:Equipo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Equipo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Equipo');
    }

    public function replicate(AuthUser $authUser, Equipo $equipo): bool
    {
        return $authUser->can('Replicate:Equipo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Equipo');
    }

}