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

    protected int | string | array $columnSpan = 6;

    protected function getStats(): array
    {
        $user = auth()->user();

        $equipoUser = EquipoUser::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->where(function ($q) { // Solo activos (sin fecha fin, o fin futuro)
                $q->whereNull('fecha_fin')
                  ->orWhere('fecha_fin', '>=', now()->toDateString());
            })
            ->orderByDesc('fecha_inicio')
            ->first();

        // Cargar el equipo directamente para evitar que el global scope lo filtre
        $equipo = null;
        if ($equipoUser && $equipoUser->equipo_id) {
            $equipo = Equipo::withoutGlobalScopes()->find($equipoUser->equipo_id);
        }

        if (!$equipoUser || !$equipo) {
            return [
                Stat::make('Mi Equipo', 'Sin equipo asignado')
                    ->icon('heroicon-o-users')
                    ->color('gray'),
            ];
        }

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
