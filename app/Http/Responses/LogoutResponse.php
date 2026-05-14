<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * Create a response that redirects the user after logging out.
     */
    public function toResponse($request): RedirectResponse
    {
        return redirect()->to('/login');
    }
}
