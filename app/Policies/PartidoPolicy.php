<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Partido;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartidoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Partido');
    }

    public function view(AuthUser $authUser, Partido $partido): bool
    {
        return $authUser->can('View:Partido');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Partido');
    }

    public function update(AuthUser $authUser, Partido $partido): bool
    {
        return $authUser->can('Update:Partido');
    }

    public function delete(AuthUser $authUser, Partido $partido): bool
    {
        return $authUser->can('Delete:Partido');
    }

    public function restore(AuthUser $authUser, Partido $partido): bool
    {
        return $authUser->can('Restore:Partido');
    }

    public function forceDelete(AuthUser $authUser, Partido $partido): bool
    {
        return $authUser->can('ForceDelete:Partido');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Partido');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Partido');
    }

    public function replicate(AuthUser $authUser, Partido $partido): bool
    {
        return $authUser->can('Replicate:Partido');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Partido');
    }

}