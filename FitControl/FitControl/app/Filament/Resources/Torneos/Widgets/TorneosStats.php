<?php

namespace App\Filament\Resources\Torneos\Widgets;

use App\Models\Torneo;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TorneosStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user && $user->hasRole('super_admin');

        $queryTorneos = Torneo::query();

        if (!$isSuperAdmin && $user && $user->tenant_id) {
            $queryTorneos->where('tenant_id', $user->tenant_id);
        }

        $cantTorneosActivos = (clone $queryTorneos)->whereIn('estado', ['activo', 'en_progreso'])->count();
        $cantTorneosFinalizados = (clone $queryTorneos)->where('estado', 'finalizado')->count();
        $cantTotalTorneos = (clone $queryTorneos)->count();

        return [
            Stat::make('Torneos Activos', $cantTorneosActivos)
                ->description('Torneos activos o en progreso')
                ->icon('heroicon-m-trophy')
                ->color('success'),

            Stat::make('Torneos Finalizados', $cantTorneosFinalizados)
                ->description('Torneos terminados')
                ->icon('heroicon-m-check-badge')
                ->color('info'),

            Stat::make('Total de Torneos', $cantTotalTorneos)
                ->description('Total registrado en el club')
                ->icon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}
