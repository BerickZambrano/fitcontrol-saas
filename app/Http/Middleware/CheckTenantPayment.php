<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantPayment
{
    public function handle(Request $request, Closure $next): Response
    {
        $userBefore = auth()->user();
        $response = $next($request);
        $userAfter = auth()->user();

        if ($userBefore || $userAfter) {
            \Illuminate\Support\Facades\Log::debug('CheckTenantPayment debug:', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'route_name' => $request->route() ? $request->route()->getName() : null,
                'user_before_id' => $userBefore ? $userBefore->id : null,
                'user_after_id' => $userAfter ? $userAfter->id : null,
                'status_code' => $response->getStatusCode(),
            ]);
        }

        $user = $userAfter;

        if ($user && $user->tenant && $user->tenant->estado_pago === 'pendiente') {
            // Permitir solicitudes de logout, simulaciones de pago y peticiones Livewire de logout/pago
            if ($request->routeIs('paywall.*') || 
                $request->routeIs('filament.*.auth.logout') || 
                $request->routeIs('logout') || 
                str_contains($request->getPathInfo(), 'logout') || 
                $request->routeIs('livewire.*')) {
                return $response;
            }

            // Si es un request HTML regular, le inyectamos el overlay con blur
            if ($response instanceof \Illuminate\Http\Response || $response instanceof Response) {
                $contentType = $response->headers->get('Content-Type');
                if ($contentType && str_contains($contentType, 'text/html')) {
                    $content = $response->getContent();
                    
                    try {
                        $paywallHtml = view('paywall_overlay', ['tenant' => $user->tenant])->render();
                        
                        // Inyectar antes de </body>
                        $pos = strripos($content, '</body>');
                        if ($pos !== false) {
                            $content = substr($content, 0, $pos) . $paywallHtml . substr($content, $pos);
                            $response->setContent($content);
                        }
                    } catch (\Exception $e) {
                        // En caso de error, dejamos pasar la respuesta original
                    }
                }
            }
        }

        return $response;
    }
}
