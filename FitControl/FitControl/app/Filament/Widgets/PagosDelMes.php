<?php

namespace App\Filament\Widgets;

use App\Models\Pago;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class PagosDelMes extends StatsOverviewWidget
{
    use HasWidgetShield;

    protected int | string | array $columnSpan = 6;
    protected static ?int $sort = 9;

    protected function getStats(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $now = Carbon::now();
        $mes = $now->month;
        $anio = $now->year;
        
        $cacheKey = $isSuperAdmin 
            ? "widget_pagos_mes_{$mes}_{$anio}" 
            : "widget_pagos_tenant_{$user->tenant_id}_{$mes}_{$anio}";

        $total = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($isSuperAdmin, $user, $mes, $anio) {
            $query = Pago::whereRaw('EXTRACT(MONTH FROM fecha) = ? AND EXTRACT(YEAR FROM fecha) = ?', [$mes, $anio]);

            if (!$isSuperAdmin) {
                $query->where('tenant_id', $user->tenant_id);
            }

            return (float) ($query->sum('monto') ?? 0);
        });

        return [
            Stat::make('Pagos este mes', $total)
                ->description('Total recaudado este mes')
                ->icon('heroicon-o-currency-dollar')
                ->color('info'),
        ];
    }
}