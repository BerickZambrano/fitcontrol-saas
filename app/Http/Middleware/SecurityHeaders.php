<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
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
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '0');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // CSP dinámico: en local permitimos Vite dev server, Wompi y subdominios
        if (app()->environment('local')) {
            $csp = "default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' blob: https://cdn.jsdelivr.net https://cdn.tailwindcss.com https://checkout.wompi.co http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:* http://*.localhost:*; " .
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net http://localhost:* http://127.0.0.1:* http://*.localhost:*; " .
                "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net; " .
                "img-src 'self' data: blob: https: http://localhost:* http://127.0.0.1:* http://*.localhost:*; " .
                "connect-src 'self' https://*.wompi.co http://localhost:* ws://localhost:* http://127.0.0.1:* ws://127.0.0.1:* http://*.localhost:* ws://*.localhost:*; " .
                "frame-src 'self' https://checkout.wompi.co; " .
                "worker-src 'self' blob:; " .
                "frame-ancestors 'none';";
        } else {
            $csp = "default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' blob: https://cdn.jsdelivr.net https://cdn.tailwindcss.com https://checkout.wompi.co; " .
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net; " .
                "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net; " .
                "img-src 'self' data: blob: https:; " .
                "connect-src 'self' https://*.wompi.co; " .
                "frame-src 'self' https://checkout.wompi.co; " .
                "worker-src 'self' blob:; " .
                "frame-ancestors 'none';";
        }

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
