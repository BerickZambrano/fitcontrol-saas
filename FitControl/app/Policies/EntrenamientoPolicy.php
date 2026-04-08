<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Entrenamiento;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntrenamientoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Entrenamiento');
    }

    public function view(AuthUser $authUser, Entrenamiento $entrenamiento): bool
    {
        return $authUser->can('View:Entrenamiento');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Entrenamiento');
    }

    public function update(AuthUser $authUser, Entrenamiento $entrenamiento): bool
    {
        return $authUser->can('Update:Entrenamiento');
    }

    public function delete(AuthUser $authUser, Entrenamiento $entrenamiento): bool
    {
        return $authUser->can('Delete:Entrenamiento');
    }

    public function restore(AuthUser $authUser, Entrenamiento $entrenamiento): bool
    {
        return $authUser->can('Restore:Entrenamiento');
    }

    public function forceDelete(AuthUser $authUser, Entrenamiento $entrenamiento): bool
    {
        return $authUser->can('ForceDelete:Entrenamiento');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Entrenamiento');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Entrenamiento');
    }

    public function replicate(AuthUser $authUser, Entrenamiento $entrenamiento): bool
    {
        return $authUser->can('Replicate:Entrenamiento');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Entrenamiento');
    }

}