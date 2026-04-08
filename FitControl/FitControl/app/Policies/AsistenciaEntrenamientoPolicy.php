<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AsistenciaEntrenamiento;
use Illuminate\Auth\Access\HandlesAuthorization;

class AsistenciaEntrenamientoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AsistenciaEntrenamiento');
    }

    public function view(AuthUser $authUser, AsistenciaEntrenamiento $asistenciaEntrenamiento): bool
    {
        return $authUser->can('View:AsistenciaEntrenamiento');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AsistenciaEntrenamiento');
    }

    public function update(AuthUser $authUser, AsistenciaEntrenamiento $asistenciaEntrenamiento): bool
    {
        return $authUser->can('Update:AsistenciaEntrenamiento');
    }

    public function delete(AuthUser $authUser, AsistenciaEntrenamiento $asistenciaEntrenamiento): bool
    {
        return $authUser->can('Delete:AsistenciaEntrenamiento');
    }

    public function restore(AuthUser $authUser, AsistenciaEntrenamiento $asistenciaEntrenamiento): bool
    {
        return $authUser->can('Restore:AsistenciaEntrenamiento');
    }

    public function forceDelete(AuthUser $authUser, AsistenciaEntrenamiento $asistenciaEntrenamiento): bool
    {
        return $authUser->can('ForceDelete:AsistenciaEntrenamiento');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AsistenciaEntrenamiento');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AsistenciaEntrenamiento');
    }

    public function replicate(AuthUser $authUser, AsistenciaEntrenamiento $asistenciaEntrenamiento): bool
    {
        return $authUser->can('Replicate:AsistenciaEntrenamiento');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AsistenciaEntrenamiento');
    }

}