<?php

namespace App\Filament\Resources\AsistenciaEntrenamientos\Widgets;

use App\Models\AsistenciaEntrenamiento;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AsistenciaStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user && $user->hasRole('super_admin');

        $queryAsistencias = AsistenciaEntrenamiento::query();

        if (!$isSuperAdmin && $user && $user->tenant_id) {
            $queryAsistencias->where('tenant_id', $user->tenant_id);
        }

        $cantPresente = (clone $queryAsistencias)->where('presente', true)->count();
        $cantAusente = (clone $queryAsistencias)->where('presente', false)->count();
        $totalAsistencias = $cantPresente + $cantAusente;

        $porcentajeAsistencia = $totalAsistencias > 0 ? round(($cantPresente / $totalAsistencias) * 100, 1) : 0;

        return [
            Stat::make('Asistencias Totales', $cantPresente)
                ->description('Jugadores presentes')
                ->icon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Inasistencias Totales', $cantAusente)
                ->description('Jugadores ausentes')
                ->icon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Tasa de Asistencia', $porcentajeAsistencia . '%')
                ->description('Porcentaje de asistencia general')
                ->icon('heroicon-m-chart-bar')
                ->color('info'),
        ];
    }
}
