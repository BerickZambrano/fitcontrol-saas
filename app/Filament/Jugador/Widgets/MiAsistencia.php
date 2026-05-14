<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\AsistenciaEntrenamiento;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class MiAsistencia extends StatsOverviewWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 2;

    protected function getStats(): array
    {
        $user = auth()->user();

        $total = AsistenciaEntrenamiento::where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->count();

        $presentes = AsistenciaEntrenamiento::where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->where('presente', true)
            ->count();

        $porcentaje = $total > 0 ? round(($presentes / $total) * 100) : 0;

        return [
            Stat::make('Mi Asistencia', $porcentaje . '%')
                ->description($presentes . ' de ' . $total . ' entrenamientos')
                ->icon('heroicon-o-check-circle')
                ->color($porcentaje >= 80 ? 'success' : ($porcentaje >= 50 ? 'warning' : 'danger')),
        ];
    }
}
