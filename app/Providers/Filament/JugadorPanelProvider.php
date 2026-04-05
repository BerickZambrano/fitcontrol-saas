<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

class JugadorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('jugador')
            ->path('jugador')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->plugins([
                FilamentApexChartsPlugin::make()
            ])
            ->discoverResources(in: app_path('Filament/Jugador/Resources'), for: 'App\Filament\Jugador\Resources')
            ->discoverPages(in: app_path('Filament/Jugador/Pages'), for: 'App\Filament\Jugador\Pages')
            ->pages([
                Dashboard::class,
                \App\Filament\Jugador\Pages\PlayerProfile::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Jugador/Widgets'), for: 'App\Filament\Jugador\Widgets')
            ->widgets([
                AccountWidget::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
