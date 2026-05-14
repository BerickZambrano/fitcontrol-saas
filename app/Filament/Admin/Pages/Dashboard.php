<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\AccionesRapidas;
use App\Filament\Jugador\Widgets\PlayerCardWidget;
use App\Filament\Widgets\ProximosEventos;
use App\Filament\Widgets\AlertasActivas;
use App\Filament\Widgets\TotalEquipos;
use App\Filament\Widgets\TorneosActivos;
use App\Filament\Widgets\PagosDelMes;
use App\Filament\Widgets\JugadoresNoAptos;
use App\Filament\Widgets\TotalUsuarios;

class Dashboard extends BaseDashboard
{
    public static function canView(): bool
    {
        return auth()->user()->can('View: Dashboard');
    }

    protected static ?string $title = 'Dashboard';

    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'sm' => 1,
            'md' => 1,
            'lg' => 12,
        ];
    }

    /**
     * Widgets organized in operational order:
     * 1. Quick actions (full width)
     * 2. Upcoming events + Active alerts
     * 3. Key metrics (teams, tournaments, payments, medical)
     */
    public function getWidgets(): array
    {
        return [
            AccionesRapidas::class,
            AlertasActivas::class,
            TotalEquipos::class,
            TotalUsuarios::class,
            JugadoresNoAptos::class,
            \App\Filament\Widgets\AsistenciaPorMes::class,
            \App\Filament\Widgets\EntrenamientosPorMes::class,
            TorneosActivos::class,
            PagosDelMes::class,
            \App\Filament\Widgets\PagosPorMes::class,
            ProximosEventos::class,
        ];
    }
}
