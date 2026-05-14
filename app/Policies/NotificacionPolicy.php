<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Notificacion;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificacionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Notificacion');
    }

    public function view(AuthUser $authUser, Notificacion $notificacion): bool
    {
        return $authUser->can('View:Notificacion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Notificacion');
    }

    public function update(AuthUser $authUser, Notificacion $notificacion): bool
    {
        return $authUser->can('Update:Notificacion');
    }

    public function delete(AuthUser $authUser, Notificacion $notificacion): bool
    {
        return $authUser->can('Delete:Notificacion');
    }

    public function restore(AuthUser $authUser, Notificacion $notificacion): bool
    {
        return $authUser->can('Restore:Notificacion');
    }

    public function forceDelete(AuthUser $authUser, Notificacion $notificacion): bool
    {
        return $authUser->can('ForceDelete:Notificacion');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Notificacion');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Notificacion');
    }

    public function replicate(AuthUser $authUser, Notificacion $notificacion): bool
    {
        return $authUser->can('Replicate:Notificacion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Notificacion');
    }

}