<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\AccionesRapidas;
use App\Filament\Widgets\ProximosEventos;
use App\Filament\Widgets\AlertasActivas;
use App\Filament\Widgets\TotalEquipos;
use App\Filament\Widgets\TorneosActivos;
use App\Filament\Widgets\PagosDelMes;
use App\Filament\Widgets\JugadoresNoAptos;

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
            'lg' => 2,
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
            ProximosEventos::class,
            AlertasActivas::class,
            TotalEquipos::class,
            TorneosActivos::class,
            PagosDelMes::class,
            JugadoresNoAptos::class,
        ];
    }
}
