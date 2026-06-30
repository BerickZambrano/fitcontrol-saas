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

class MedicoPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('medico')
            ->path('medico')
            ->brandName('FitControl - Área Médica')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('5rem')
            ->favicon(asset('images/favicon.png'))
            ->colors([
                'primary' => Color::Cyan,
            ])
            ->renderHook(
                'panels::head.end',
                fn (): HtmlString => new HtmlString(
                    auth()->check() && ($color = auth()->user()->tenant?->colores_oficiales['primary'] ?? null)
                        ? "<style>:root { " . implode(' ', array_map(fn ($key, $value) => "--primary-{$key}: {$value};", array_keys(Color::hex($color)), Color::hex($color))) . " }</style>"
                        : ''
                )
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Medico/Pages'), for: 'App\Filament\Medico\Pages')
            ->pages([
                \Filament\Pages\Dashboard::class,
                \App\Filament\Medico\Pages\MedicoProfile::class,
                \App\Filament\Admin\Pages\Calendario::class,
            ])
            ->widgets([
                \App\Filament\Resources\HistorialMedicos\Widgets\HistorialMedicosStats::class,
                \App\Filament\Widgets\JugadoresNoAptos::class,
                \App\Filament\Entrenador\Widgets\JugadoresNoAptosWidget::class,
            ])
            ->plugins([
                \Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin::make(),
            ])
            ->userMenuItems([
                'profile' => \Filament\Navigation\MenuItem::make()
                    ->label('Mi Perfil')
                    ->url(fn (): string => \App\Filament\Medico\Pages\MedicoProfile::getUrl())
                    ->icon('heroicon-o-user-circle'),
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling(30)
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
                \App\Http\Middleware\CheckTenantPayment::class,
                \App\Http\Middleware\SessionLifecycleMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
