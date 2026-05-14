<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Pago;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Pago');
    }

    public function view(AuthUser $authUser, Pago $pago): bool
    {
        return $authUser->can('View:Pago');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Pago');
    }

    public function update(AuthUser $authUser, Pago $pago): bool
    {
        return $authUser->can('Update:Pago');
    }

    public function delete(AuthUser $authUser, Pago $pago): bool
    {
        return $authUser->can('Delete:Pago');
    }

    public function restore(AuthUser $authUser, Pago $pago): bool
    {
        return $authUser->can('Restore:Pago');
    }

    public function forceDelete(AuthUser $authUser, Pago $pago): bool
    {
        return $authUser->can('ForceDelete:Pago');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Pago');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Pago');
    }

    public function replicate(AuthUser $authUser, Pago $pago): bool
    {
        return $authUser->can('Replicate:Pago');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Pago');
    }

}