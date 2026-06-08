<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\HtmlString;

class ArbitroPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('arbitro')
            ->path('arbitro')
            ->viteTheme('resources/css/filament/jugador/theme.css') // we can reuse player theme or admin theme for now
            ->brandName('FitControl - Árbitros')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('5rem')
            ->favicon(asset('images/logo.ico'))
            ->colors([
                'primary' => Color::Amber,
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling(30)
            ->renderHook(
                'panels::head.end',
                fn (): HtmlString => new HtmlString(
                    auth()->check() && ($color = auth()->user()->tenant?->colores_oficiales['primary'] ?? null)
                        ? "<style>:root { " . implode(' ', array_map(fn ($key, $value) => "--primary-{$key}: {$value};", array_keys(Color::hex($color)), Color::hex($color))) . " }</style>"
                        : ''
                )
            )
            ->discoverResources(in: app_path('Filament/Arbitro/Resources'), for: 'App\Filament\Arbitro\Resources')
            ->discoverPages(in: app_path('Filament/Arbitro/Pages'), for: 'App\Filament\Arbitro\Pages')
            ->pages([
                \Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Arbitro/Widgets'), for: 'App\Filament\Arbitro\Widgets')
            ->widgets([
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\ApplyTenantColor::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
