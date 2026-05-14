<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        // Validar que el usuario exista y que el OTP no haya sido consumido
        if (!$user || empty($user->two_factor_otp)) {
            $this->error = 'El código ingresado es incorrecto o ha expirado.';
            return;
        }

        // Validar expiración del OTP
        if (now()->greaterThan($user->two_factor_otp_expires_at)) {
            $user->resetTwoFactorOtp(); // limpiar el OTP expirado
            $this->error = 'El código ha expirado. Por favor solicita uno nuevo.';
            return;
        }

        // Validar el OTP contra el hash almacenado en la BD
        if (!Hash::check($this->code, $user->two_factor_otp)) {
            $this->error = 'El código ingresado es incorrecto.';
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
