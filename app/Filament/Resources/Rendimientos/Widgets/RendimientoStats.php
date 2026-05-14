<?php

namespace App\Filament\Resources\Rendimientos\Widgets;

use App\Models\Rendimiento;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RendimientoStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user && $user->hasRole('super_admin');

        $queryRendimientos = Rendimiento::query();

        if (!$isSuperAdmin && $user && $user->tenant_id) {
            $queryRendimientos->where('tenant_id', $user->tenant_id);
        }

        $totalGoles = (clone $queryRendimientos)->sum('goles');
        $totalAsistencias = (clone $queryRendimientos)->sum('asistencias');
        $promedioMinutos = (clone $queryRendimientos)->avg('minutos_jugados') ?: 0;

        return [
            Stat::make('Goles Totales', $totalGoles)
                ->description('Goles marcados en partidos')
                ->icon('heroicon-m-fire')
                ->color('success'),

            Stat::make('Asistencias Totales', $totalAsistencias)
                ->description('Pases de gol completados')
                ->icon('heroicon-m-hand-thumb-up')
                ->color('info'),

            Stat::make('Minutos Promedio', round($promedioMinutos, 1) . "'")
                ->description('Minutos jugados por partido')
                ->icon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
