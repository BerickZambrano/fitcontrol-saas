<?php

namespace App\Filament\Resources\Equipos\Widgets;

use App\Models\Equipo;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EquiposStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user && $user->hasRole('super_admin');

        $queryEquipos = Equipo::query();

        if (!$isSuperAdmin && $user && $user->tenant_id) {
            $queryEquipos->where('tenant_id', $user->tenant_id);
        }

        $cantEquipos = $queryEquipos->count();
        
        // Contar el total de jugadores que pertenecen a los equipos del tenant
        $cantJugadores = \DB::table('historial_equipo')
            ->whereIn('id_equipo_fk', function($query) use ($isSuperAdmin, $user) {
                $query->select('id')->from('equipos');
                if (!$isSuperAdmin && $user && $user->tenant_id) {
                    $query->where('tenant_id', $user->tenant_id);
                }
            })->count();

        $promedioJugadores = $cantEquipos > 0 ? round($cantJugadores / $cantEquipos, 1) : 0;

        return [
            Stat::make('Total Equipos', $cantEquipos)
                ->description('Equipos registrados en el club')
                ->icon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Total Jugadores', $cantJugadores)
                ->description('Jugadores inscritos en los equipos')
                ->icon('heroicon-m-users')
                ->color('info'),

            Stat::make('Promedio por Equipo', $promedioJugadores)
                ->description('Número promedio de jugadores')
                ->icon('heroicon-m-chart-bar')
                ->color('warning'),
        ];
    }
}
