<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\Rendimiento;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class MisEstadisticas extends StatsOverviewWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();

        $stats = Rendimiento::where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->selectRaw('
                SUM(minutos_jugados) as total_minutos,
                SUM(goles) as total_goles,
                SUM(asistencias) as total_asistencias
            ')
            ->first();

        $goles = (int) ($stats->total_goles ?? 0);
        $asistencias = (int) ($stats->total_asistencias ?? 0);
        $minutos = (int) ($stats->total_minutos ?? 0);

        return [
            Stat::make('⚽ Goles', $goles)
                ->color('success'),

            Stat::make('🎯 Asistencias', $asistencias)
                ->color('info'),

            Stat::make('⏱️ Minutos', $minutos)
                ->color('warning'),
        ];
    }
}
