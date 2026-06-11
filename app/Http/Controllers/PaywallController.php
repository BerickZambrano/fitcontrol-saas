<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaywallController extends Controller
{
    private function getRedirectPath($user): string
    {
        if (!$user) {
            return '/';
        }
        if ($user->hasRole(['super_admin', 'Administrador'])) {
            return '/admin';
        }
        if ($user->hasRole('Entrenador')) {
            return '/entrenador';
        }
        if ($user->hasRole('Jugador')) {
            return '/jugador';
        }
        if ($user->hasRole('Arbitro')) {
            return '/arbitro';
        }
        return '/';
    }

    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/');
        }

        $tenant = $user->tenant;
        if (!$tenant || $tenant->estado_pago === 'pagado') {
            return redirect($this->getRedirectPath($user)); 
        }

        return view('paywall', compact('tenant'));
    }

    public function simulatePayment(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $tenant = $user->tenant;
            if ($tenant) {
                $tenant->update(['estado_pago' => 'pagado']);
            }
            return redirect($this->getRedirectPath($user))->with('success', 'Pago procesado exitosamente. Funciones desbloqueadas.');
        }
        return redirect('/');
    }
}
