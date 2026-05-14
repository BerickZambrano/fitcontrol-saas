<?php

namespace App\Filament\Resources\Partidos\Widgets;

use App\Models\Partido;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PartidosStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user && $user->hasRole('super_admin');

        $queryPartidos = Partido::query();

        if (!$isSuperAdmin && $user && $user->tenant_id) {
            $queryPartidos->where('tenant_id', $user->tenant_id);
        }

        $totalPartidos = (clone $queryPartidos)->count();
        $jugados = (clone $queryPartidos)->whereNotNull('resultado')->where('resultado', '!=', '')->count();
        $pendientes = $totalPartidos - $jugados;

        return [
            Stat::make('Total Partidos', $totalPartidos)
                ->description('Partidos programados')
                ->icon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Partidos Jugados', $jugados)
                ->description('Partidos con resultado')
                ->icon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Partidos Pendientes', $pendientes)
                ->description('Partidos por jugar')
                ->icon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
