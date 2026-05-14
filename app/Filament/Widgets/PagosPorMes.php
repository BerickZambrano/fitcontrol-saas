<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use App\Models\Pago;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class PagosPorMes extends ChartWidget
{
    use HasWidgetShield;

    protected int | string | array $columnSpan = 6;
    protected static ?int $sort = 10;
    protected ?string $heading = 'Pagos por mes';

    protected function getData(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $cacheKey = $isSuperAdmin 
            ? 'widget_pagos_12meses' 
            : "widget_pagos_tenant_{$user->tenant_id}_12meses";

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($isSuperAdmin, $user) {
            $query = Pago::select(
                DB::raw('EXTRACT(MONTH FROM created_at) as mes'),
                DB::raw('SUM(monto) as total')
            );

            // Limitar a últimos 12 meses
            $fechaLimite = Carbon::now()->subMonths(12);
            $query->where('created_at', '>=', $fechaLimite);

            if (!$isSuperAdmin) {
                $query->where('tenant_id', $user->tenant_id);
            }

            $pagos = $query
                ->groupBy('mes')
                ->orderBy('mes')
                ->get();

            return [
                'datasets' => [
                    [
                        'label' => 'Total de pagos',
                        'data' => $pagos->pluck('total')->map(fn ($v) => (float) $v)->toArray(),
                        'color' => '#3b82f6',
                    ],
                ],
                'labels' => $pagos->pluck('mes')->map(fn ($mes) => match ((int) $mes) {
                    1 => 'Enero',
                    2 => 'Febrero',
                    3 => 'Marzo',
                    4 => 'Abril',
                    5 => 'Mayo',
                    6 => 'Junio',
                    7 => 'Julio',
                    8 => 'Agosto',
                    9 => 'Septiembre',
                    10 => 'Octubre',
                    11 => 'Noviembre',
                    12 => 'Diciembre',
                }),
            ];
        });
    }

    protected function getType(): string
    {
        return 'pie';
    }
}