<?php

namespace App\Filament\Widgets;

use App\Models\HistorialMedico;
use App\Models\Pago;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class AlertasActivas extends StatsOverviewWidget
{
    use HasWidgetShield;

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $tenantId = $user->tenant_id;

        $cacheKey = $isSuperAdmin
            ? 'widget_alertas_super_admin'
            : "widget_alertas_tenant_{$tenantId}";

        $alertas = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($isSuperAdmin, $tenantId) {
            $baseQuery = function ($query) use ($isSuperAdmin, $tenantId) {
                if (!$isSuperAdmin) {
                    $query->where('tenant_id', $tenantId);
                }
                return $query;
            };

            // Jugadores con lesiones activas (no aptos)
            $lesionesActivas = (clone $baseQuery)(HistorialMedico::query())
                ->where(function ($q) {
                    $q->whereNull('fecha_fin')
                      ->orWhere('apto', false);
                })
                ->count();

            // Pagos vencidos (fecha < hoy y estado = pendiente)
            $pagosVencidos = (clone $baseQuery)(Pago::query())
                ->where('fecha', '<', Carbon::today())
                ->where('estado', 'pendiente')
                ->count();

            return [
                'lesiones_activas' => $lesionesActivas,
                'pagos_vencidos' => $pagosVencidos,
            ];
        });

        $stats = [];

        $stats[] = Stat::make(
            'Lesiones activas',
            $alertas['lesiones_activas']
        )
            ->description('Jugadores no aptos')
            ->icon('heroicon-o-heart')
            ->color($alertas['lesiones_activas'] > 0 ? 'danger' : 'success');

        $stats[] = Stat::make(
            'Pagos vencidos',
            $alertas['pagos_vencidos']
        )
            ->description('Cuotas pendientes fuera de fecha')
            ->icon('heroicon-o-credit-card')
            ->color($alertas['pagos_vencidos'] > 0 ? 'warning' : 'success');

        return $stats;
    }
}
