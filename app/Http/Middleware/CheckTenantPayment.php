<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantPayment
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->tenant && $user->tenant->estado_pago === 'pendiente') {
            if ($user->hasRole('Administrador') || $user->hasRole('Entrenador')) {
                // Permitir logout y acceso directo a la vista de paywall o livewire/webhooks
                if ($request->routeIs('paywall.*') || $request->routeIs('filament.*.auth.logout') || $request->routeIs('livewire.*')) {
                    return $next($request);
                }

                // Si es un request de Livewire, pero no es de la ruta de paywall, 
                // redirigimos mediante un header especial de Livewire o un abort normal si es API
                if ($request->header('X-Livewire')) {
                    return response()->json(['redirect' => route('paywall.index')], 401);
                }

                return redirect()->route('paywall.index');
            }
        }

        return $next($request);
    }
}
