<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SessionLifecycleMiddleware
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

        // Only handle successful GET requests returning HTML, excluding AJAX/Inertia/Livewire requests
        if ($request->isMethod('GET')
            && $response->getStatusCode() === 200
            && !$request->ajax()
            && !$request->hasHeader('X-Inertia')
            && !$request->hasHeader('X-Livewire')
            && str_contains($response->headers->get('Content-Type') ?? '', 'text/html')
        ) {
            $content = $response->getContent();

            if (Auth::check()) {
                // If authenticated, we check if sessionStorage has 'session_active'.
                // If not, we submit a POST request to logout the session.
                $csrfToken = csrf_token();
                $script = <<<HTML
<script>
    (function() {
        if (!sessionStorage.getItem('session_active')) {
            let logoutUrl = '/logout';
            const path = window.location.pathname;
            if (path.startsWith('/admin')) {
                logoutUrl = '/admin/logout';
            } else if (path.startsWith('/entrenador')) {
                logoutUrl = '/entrenador/logout';
            } else if (path.startsWith('/jugador')) {
                logoutUrl = '/jugador/logout';
            } else if (path.startsWith('/medico')) {
                logoutUrl = '/medico/logout';
            } else if (path.startsWith('/arbitro')) {
                logoutUrl = '/arbitro/logout';
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = logoutUrl;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{$csrfToken}';
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    })();
</script>
HTML;
            } else {
                // If guest, initialize the session_active key in sessionStorage.
                $script = <<<HTML
<script>
    (function() {
        sessionStorage.setItem('session_active', '1');
    })();
</script>
HTML;
            }

            // Inject the script right before the closing </body> tag
            $pos = strripos($content, '</body>');
            if ($pos !== false) {
                $content = substr($content, 0, $pos) . $script . substr($content, $pos);
                $response->setContent($content);
            }
        }

        return $response;
    }
}
