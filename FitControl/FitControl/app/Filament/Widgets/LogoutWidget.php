<?php

namespace App\Filament\Widgets;

use Filament\Navigation\UserMenuItem;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;


class LogoutWidget
{
    public static function make(): UserMenuItem
    {
        return UserMenuItem::make('Cerrar sesión')
            ->icon('heroicon-s-arrow-left-on-circle') // Ícono moderno
            ->color('danger')                          // Color rojo
            ->action(fn () => auth()->logout())        // Cierra la sesión
            ->requiresConfirmation()                  // Modal de confirmación
            ->tooltip('Salir de la sesión actual')    // Tooltip al pasar el mouse
            ->openUrl(route('filament.auth.logout')); // Redirige al login
    }
}
