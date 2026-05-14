<?php

namespace App\Filament\Jugador\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Jugador\Widgets\PlayerCardWidget;
use App\Filament\Jugador\Widgets\ProximoCompromiso;
use App\Filament\Jugador\Widgets\MisEstadisticas;
use App\Filament\Jugador\Widgets\MiAsistencia;
use App\Filament\Jugador\Widgets\MiEquipo;
use App\Filament\Jugador\Widgets\ProximoEntrenamiento;
use App\Filament\Jugador\Widgets\ProximoPartido;
use App\Filament\Jugador\Widgets\HistorialPagos;
use App\Filament\Jugador\Widgets\AsistenciaPorMes;
use App\Filament\Jugador\Widgets\EntrenamientosPorMes;
use App\Filament\Jugador\Widgets\ProximosCompromisos;
use App\Filament\Jugador\Widgets\MisNotificaciones;
use BackedEnum;

class Dashboard extends BaseDashboard
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Mi Dashboard';

    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'sm' => 1,
            'md' => 1,
            'lg' => 6,
        ];
    }

    public function getWidgets(): array
    {
        return [
            PlayerCardWidget::class,
            ProximoCompromiso::class,
            MisEstadisticas::class,
            MiAsistencia::class,
            MiEquipo::class,
            ProximoEntrenamiento::class,
            ProximoPartido::class,
            HistorialPagos::class,
            AsistenciaPorMes::class,
            EntrenamientosPorMes::class,
            ProximosCompromisos::class,
            MisNotificaciones::class,
        ];
    }
}
