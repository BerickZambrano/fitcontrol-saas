<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaywallController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/entrenador/login');
        }

        $tenant = $user->tenant;
        if (!$tenant || $tenant->estado_pago === 'pagado') {
            return redirect('/entrenador'); 
        }

        return view('paywall', compact('tenant'));
    }

    public function simulatePayment(Request $request)
    {
        $tenant = auth()->user()->tenant;
        if ($tenant) {
            $tenant->update(['estado_pago' => 'pagado']);
        }
        return redirect('/entrenador')->with('success', 'Pago procesado exitosamente. Funciones desbloqueadas.');
    }
}
