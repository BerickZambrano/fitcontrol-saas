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

        if ($user->two_factor_type === 'email') {
            // Generar y enviar código
            $code = $user->generateTwoFactorOtp();
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\Auth\TwoFactorCode($code));

            // Almacenar el ID del usuario en sesión y cerrar sesión temporalmente
            $userId = $user->id;
            Auth::logout();
            $request->session()->put('2fa:user_id', $userId);
            $request->session()->put('2fa:remember', $request->boolean('remember'));

            return redirect()->route('2fa.verify');
        }

        if ($user->hasRole(['Administrador', 'super_admin'])) {
            return redirect('/admin');
        }

        if ($user->hasRole('Entrenador')) {
            return redirect('/entrenador');
        }

        if ($user->hasRole('Arbitro')) {
            return redirect('/arbitro');
        }

        return redirect('/jugador');
    }
}
