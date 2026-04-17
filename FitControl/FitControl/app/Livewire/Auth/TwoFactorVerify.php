<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TwoFactorVerify extends Component
{
    public string $code = '';
    public ?string $error = null;

    #[Layout('components.layouts.auth')]
    public function mount()
    {
        if (!session()->has('2fa:user_id')) {
            return redirect()->route('login');
        }
    }

    public function verify()
    {
        $this->validate([
            'code' => 'required|string|size:6',
        ]);

        $userId = session('2fa:user_id');
        $user = User::find($userId);

        if (!$user || $user->two_factor_otp !== $this->code || now()->greaterThan($user->two_factor_otp_expires_at)) {
            $this->error = 'El código ingresado es incorrecto o ha expirado.';
            return;
        }

        // Éxito: Limpiar OTP y autenticar
        $user->resetTwoFactorOtp();
        Auth::login($user, session('2fa:remember', false));

        // Limpiar sesión temporal
        session()->forget(['2fa:user_id', '2fa:remember']);

        // Redirigir según rol
        if ($user->hasRole(['Administrador', 'super_admin'])) {
            return redirect('/admin');
        }

        return redirect('/jugador');
    }

    public function render()
    {
        return view('livewire.auth.two-factor-verify')->title('Verificación de Seguridad');
    }
}
