<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\HistorialMedico;
use Illuminate\Auth\Access\HandlesAuthorization;

class HistorialMedicoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:HistorialMedico');
    }

    public function view(AuthUser $authUser, HistorialMedico $historialMedico): bool
    {
        return $authUser->can('View:HistorialMedico');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:HistorialMedico');
    }

    public function update(AuthUser $authUser, HistorialMedico $historialMedico): bool
    {
        return $authUser->can('Update:HistorialMedico');
    }

    public function delete(AuthUser $authUser, HistorialMedico $historialMedico): bool
    {
        return $authUser->can('Delete:HistorialMedico');
    }

    public function restore(AuthUser $authUser, HistorialMedico $historialMedico): bool
    {
        return $authUser->can('Restore:HistorialMedico');
    }

    public function forceDelete(AuthUser $authUser, HistorialMedico $historialMedico): bool
    {
        return $authUser->can('ForceDelete:HistorialMedico');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:HistorialMedico');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:HistorialMedico');
    }

    public function replicate(AuthUser $authUser, HistorialMedico $historialMedico): bool
    {
        return $authUser->can('Replicate:HistorialMedico');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:HistorialMedico');
    }

}