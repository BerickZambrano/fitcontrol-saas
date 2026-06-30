<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->append(\App\Http\Middleware\ResolveTenantBySubdomain::class);
        $middleware->statefulApi();
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        $middleware->web([
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\SessionLifecycleMiddleware::class,
        ]);
        $middleware->alias([
            'onboarding' => \App\Http\Middleware\RedirectIfOnboardingPending::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'api/wompi/webhook',
            'wompi/webhook',
            'paywall/prepare',
            'logout',
            'admin/logout',
            'entrenador/logout',
            'jugador/logout',
            'medico/logout',
            'arbitro/logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
