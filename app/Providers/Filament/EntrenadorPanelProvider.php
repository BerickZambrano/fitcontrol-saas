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
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

class EntrenadorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('entrenador')
            ->path('entrenador')
            ->viteTheme('resources/css/filament/entrenador/theme.css')
            ->brandName('FitControl')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('5rem')
            ->favicon(asset('images/logo.ico'))
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->renderHook(
                'panels::head.end',
                fn (): HtmlString => new HtmlString(
                    auth()->check() && ($color = auth()->user()->tenant?->colores_oficiales['primary'] ?? null)
                        ? "<style>:root { " . implode(' ', array_map(fn ($key, $value) => "--primary-{$key}: {$value};", array_keys(Color::hex($color)), Color::hex($color))) . " }</style>"
                        : ''
                )
            )
            ->plugins([
                FilamentApexChartsPlugin::make(),
            ])
            ->discoverResources(in: app_path('Filament/Entrenador/Resources'), for: 'App\Filament\Entrenador\Resources')
            ->discoverPages(in: app_path('Filament/Entrenador/Pages'), for: 'App\Filament\Entrenador\Pages')
            ->pages([
                \App\Filament\Entrenador\Pages\Dashboard::class,
                \App\Filament\Entrenador\Pages\CoachProfile::class,
            ])
            ->userMenuItems([
                'profile' => \Filament\Navigation\MenuItem::make()
                    ->label('Mi Perfil')
                    ->url(fn (): string => \App\Filament\Entrenador\Pages\CoachProfile::getUrl())
                    ->icon('heroicon-o-user-circle'),
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling(30)
            ->discoverWidgets(in: app_path('Filament/Entrenador/Widgets'), for: 'App\Filament\Entrenador\Widgets')
            ->widgets([
                \App\Filament\Entrenador\Widgets\CoachStatsWidget::class,
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
