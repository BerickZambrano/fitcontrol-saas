<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JugadorPerfil;
use Illuminate\Auth\Access\HandlesAuthorization;

class JugadorPerfilPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JugadorPerfil');
    }

    public function view(AuthUser $authUser, JugadorPerfil $jugadorPerfil): bool
    {
        return $authUser->can('View:JugadorPerfil');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JugadorPerfil');
    }

    public function update(AuthUser $authUser, JugadorPerfil $jugadorPerfil): bool
    {
        return $authUser->can('Update:JugadorPerfil');
    }

    public function delete(AuthUser $authUser, JugadorPerfil $jugadorPerfil): bool
    {
        return $authUser->can('Delete:JugadorPerfil');
    }

    public function restore(AuthUser $authUser, JugadorPerfil $jugadorPerfil): bool
    {
        return $authUser->can('Restore:JugadorPerfil');
    }

    public function forceDelete(AuthUser $authUser, JugadorPerfil $jugadorPerfil): bool
    {
        return $authUser->can('ForceDelete:JugadorPerfil');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JugadorPerfil');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JugadorPerfil');
    }

    public function replicate(AuthUser $authUser, JugadorPerfil $jugadorPerfil): bool
    {
        return $authUser->can('Replicate:JugadorPerfil');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JugadorPerfil');
    }

}