<?php

namespace App\Filament\Arbitro\Widgets;

use App\Models\Partido;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ArbitroStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        $partidosPendientes = Partido::where('arbitro_id', $user->id)
            ->where('estado_arbitro', 'pendiente')
            ->count();

        $proximosPartidos = Partido::where('arbitro_id', $user->id)
            ->where('estado_arbitro', 'aceptado')
            ->whereIn('estado_partido', ['programado', 'en_juego'])
            ->count();

        $partidosFinalizados = Partido::where('arbitro_id', $user->id)
            ->where('estado_arbitro', 'aceptado')
            ->where('estado_partido', 'finalizado')
            ->count();

        return [
            Stat::make('Partidos por Aceptar', $partidosPendientes)
                ->description('Asignaciones de arbitraje pendientes')
                ->descriptionIcon('heroicon-m-clock')
                ->color($partidosPendientes > 0 ? 'warning' : 'gray')
                ->icon('heroicon-o-document-check'),

            Stat::make('Próximos Partidos', $proximosPartidos)
                ->description('Partidos programados o en juego')
                ->descriptionIcon('heroicon-m-play')
                ->color('success')
                ->icon('heroicon-o-calendar-days'),

            Stat::make('Partidos Finalizados', $partidosFinalizados)
                ->description('Historial de partidos arbitrados')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('info')
                ->icon('heroicon-o-trophy'),
        ];
    }
}
