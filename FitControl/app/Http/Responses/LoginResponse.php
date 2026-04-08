<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as FilamentLoginResponse;

class LoginResponse implements LoginResponseContract, FilamentLoginResponse
{
    public function toResponse($request)
    {
        $user = Auth::user();

        if ($user->hasRole(['Administrador', 'super_admin'])) {
            return redirect('/admin');
        }

        return redirect('/jugador');
    }
}
