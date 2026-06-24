<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Traspaso;
use Illuminate\Auth\Access\HandlesAuthorization;

class TraspasoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Traspaso');
    }

    public function view(AuthUser $authUser, Traspaso $traspaso): bool
    {
        return $authUser->can('View:Traspaso');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Traspaso');
    }

    public function update(AuthUser $authUser, Traspaso $traspaso): bool
    {
        return $authUser->can('Update:Traspaso');
    }

    public function delete(AuthUser $authUser, Traspaso $traspaso): bool
    {
        return $authUser->can('Delete:Traspaso');
    }

    public function restore(AuthUser $authUser, Traspaso $traspaso): bool
    {
        return $authUser->can('Restore:Traspaso');
    }

    public function forceDelete(AuthUser $authUser, Traspaso $traspaso): bool
    {
        return $authUser->can('ForceDelete:Traspaso');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Traspaso');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Traspaso');
    }

    public function replicate(AuthUser $authUser, Traspaso $traspaso): bool
    {
        return $authUser->can('Replicate:Traspaso');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Traspaso');
    }

}