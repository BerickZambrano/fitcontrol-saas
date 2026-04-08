<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Torneo;
use Illuminate\Auth\Access\HandlesAuthorization;

class TorneoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Torneo');
    }

    public function view(AuthUser $authUser, Torneo $torneo): bool
    {
        return $authUser->can('View:Torneo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Torneo');
    }

    public function update(AuthUser $authUser, Torneo $torneo): bool
    {
        return $authUser->can('Update:Torneo');
    }

    public function delete(AuthUser $authUser, Torneo $torneo): bool
    {
        return $authUser->can('Delete:Torneo');
    }

    public function restore(AuthUser $authUser, Torneo $torneo): bool
    {
        return $authUser->can('Restore:Torneo');
    }

    public function forceDelete(AuthUser $authUser, Torneo $torneo): bool
    {
        return $authUser->can('ForceDelete:Torneo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Torneo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Torneo');
    }

    public function replicate(AuthUser $authUser, Torneo $torneo): bool
    {
        return $authUser->can('Replicate:Torneo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Torneo');
    }

}