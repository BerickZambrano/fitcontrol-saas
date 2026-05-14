<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Instalacion;
use Illuminate\Auth\Access\HandlesAuthorization;

class InstalacionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Instalacion');
    }

    public function view(AuthUser $authUser, Instalacion $instalacion): bool
    {
        return $authUser->can('View:Instalacion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Instalacion');
    }

    public function update(AuthUser $authUser, Instalacion $instalacion): bool
    {
        return $authUser->can('Update:Instalacion');
    }

    public function delete(AuthUser $authUser, Instalacion $instalacion): bool
    {
        return $authUser->can('Delete:Instalacion');
    }

    public function restore(AuthUser $authUser, Instalacion $instalacion): bool
    {
        return $authUser->can('Restore:Instalacion');
    }

    public function forceDelete(AuthUser $authUser, Instalacion $instalacion): bool
    {
        return $authUser->can('ForceDelete:Instalacion');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Instalacion');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Instalacion');
    }

    public function replicate(AuthUser $authUser, Instalacion $instalacion): bool
    {
        return $authUser->can('Replicate:Instalacion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Instalacion');
    }

}