<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        $host  = $request->getHost();
        $parts = explode('.', $host);

        // Si tenemos más de 2 partes (ej: club.localhost o club.fitcontrol.com)
        if (count($parts) >= 2) {
            $subdomain = $parts[0];

            // Ignorar subdominios reservados del sistema
            if (!in_array($subdomain, ['www', 'admin', 'localhost', '127'])) {

                // Cache de 5 minutos por subdominio — evita una query en cada request
                $tenant = Cache::remember(
                    "tenant_subdomain_{$subdomain}",
                    300,
                    fn () => Tenant::where('subdominio', $subdomain)->first()
                );

                if ($tenant) {
                    $request->attributes->set('tenant', $tenant);
                    config(['app.url' => $request->getSchemeAndHttpHost()]);
                }
            }
        }

        return $next($request);
    }
}
