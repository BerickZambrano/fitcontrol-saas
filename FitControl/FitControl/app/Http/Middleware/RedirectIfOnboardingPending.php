<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfOnboardingPending
{
    /**
     * Handle an incoming request.
     *
     * If the authenticated user's tenant has not completed onboarding,
     * redirect them to the onboarding page. Super admins are exempt.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || $user->hasRole('super_admin')) {
            return $next($request);
        }

        $tenant = $user->tenant;

        if ($tenant && $tenant->needsOnboarding()) {
            $currentRoute = $request->route()->getName();

            // Check if we're already on the onboarding page
            $isOnboardingPage = str_contains($currentRoute ?? '', 'post-register-onboarding');

            if (!$isOnboardingPage) {
                return redirect()->route('filament.admin.pages.post-register-onboarding');
            }
        }

        return $next($request);
    }
}
