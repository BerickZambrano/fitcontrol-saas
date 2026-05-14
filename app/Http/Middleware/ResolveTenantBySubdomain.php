<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantBySubdomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $parts = explode('.', $host);

        // Si tenemos más de 2 partes (ej: club.localhost o club.fitcontrol.com)
        if (count($parts) >= 2) {
            $subdomain = $parts[0];
            
            // Si el subdominio no es 'www' o 'admin'
            if (!in_array($subdomain, ['www', 'admin', 'localhost', '127'])) {
                $tenant = Tenant::where('subdominio', $subdomain)->first();
                
                if ($tenant) {
                    // Guardar el tenant en el request para uso posterior
                    $request->attributes->set('tenant', $tenant);
                    
                    // Opcionalmente, forzar la URL base de la app para que coincida con el host actual
                    config(['app.url' => $request->getSchemeAndHttpHost()]);
                }
            }
        }

        return $next($request);
    }
}
