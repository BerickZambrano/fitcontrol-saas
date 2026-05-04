<?php

namespace App\Filament\Resources\Pagos\Widgets;

use App\Models\Pago;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PagosStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user && $user->hasRole('super_admin');

        $queryTotal = Pago::query();
        $queryPendientes = Pago::query();
        $queryRechazados = Pago::query();

        if (!$isSuperAdmin && $user && $user->tenant_id) {
            $queryTotal->where('tenant_id', $user->tenant_id);
            $queryPendientes->where('tenant_id', $user->tenant_id);
            $queryRechazados->where('tenant_id', $user->tenant_id);
        }

        $totalRecaudado = (float) $queryTotal->where('estado', 'pagado')->sum('monto');
        $montoPendientes = (float) $queryPendientes->where('estado', 'pendiente')->sum('monto');
        $cantRechazados = $queryRechazados->where('estado', 'rechazado')->count();

        return [
            Stat::make('Total Recaudado', '$' . number_format($totalRecaudado, 2))
                ->description('Monto total de pagos aprobados')
                ->icon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Monto Pendiente', '$' . number_format($montoPendientes, 2))
                ->description('Monto de pagos pendientes por aprobar')
                ->icon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Pagos Rechazados', $cantRechazados)
                ->description('Cantidad de pagos rechazados')
                ->icon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
