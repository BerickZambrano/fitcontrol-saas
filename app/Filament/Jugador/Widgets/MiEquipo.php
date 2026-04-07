<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\EquipoUser;
use App\Models\Equipo;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class MiEquipo extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 4;

    protected function getStats(): array
    {
        $user = auth()->user();

        $equipoUser = EquipoUser::where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->with('equipo')
            ->first();

        if (!$equipoUser || !$equipoUser->equipo) {
            return [
                Stat::make('Mi Equipo', 'Sin equipo asignado')
                    ->icon('heroicon-o-users')
                    ->color('gray'),
            ];
        }

        $equipo = $equipoUser->equipo;

        return [
            Stat::make('Equipo', $equipo->nombre)
                ->description($equipo->categoria ?? 'Sin categoría')
                ->icon('heroicon-o-shield-check')
                ->color('primary'),

            Stat::make('Ubicación', $equipo->ubi_equipo ?? 'N/A')
                ->icon('heroicon-o-map-pin')
                ->color('info'),

            Stat::make('Desde', $equipoUser->fecha_inicio ? Carbon::parse($equipoUser->fecha_inicio)->format('d/m/Y') : 'N/A')
                ->icon('heroicon-o-calendar')
                ->color('success'),
        ];
    }
}
