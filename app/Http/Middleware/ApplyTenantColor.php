<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyTenantColor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $tenant = $user->tenant;

            if ($tenant && !empty($tenant->colores_oficiales['primary'])) {
                $primaryHex = $tenant->colores_oficiales['primary'];
                
                $colorPalette = Color::hex($primaryHex);

                // Forzamos el color en el panel actual de Filament
                if ($panel = \Filament\Facades\Filament::getCurrentPanel()) {
                    $panel->colors([
                        'primary' => $colorPalette,
                    ]);
                }

                // También lo registramos globalmente por si acaso
                FilamentColor::register([
                    'primary' => $colorPalette,
                ]);
            }
        }

        return $next($request);
    }
}
