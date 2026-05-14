<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\Pago;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class HistorialPagos extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'historialPagos';
    protected static ?string $heading = 'Historial de Pagos';

    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 'full';

    protected function getOptions(): array
    {
        $user = auth()->user();
        $cacheKey = "widget_pagos_{$user->id}";

        $data = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($user) {
            $pagos = Pago::where('user_id', $user->id)
                ->where('tenant_id', $user->tenant_id)
                ->orderBy('fecha')
                ->get();

            $labels = $pagos->map(fn ($p) => Carbon::parse($p->fecha)->format('d/m/Y'))->toArray();
            $montos = $pagos->pluck('monto')->map(fn ($v) => (float) $v)->toArray();
            $estados = $pagos->pluck('estado')->map(function ($estado) {
                return in_array(strtolower($estado), ['pagado', 'paid', 'completado']) ? 1 : 0;
            })->toArray();

            return [
                'labels' => $labels,
                'montos' => $montos,
                'estados' => $estados,
            ];
        });

        return [
            'chart' => [
                'type' => 'area',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Monto Pagado',
                    'data' => $data['montos'],
                ],
            ],
            'xaxis' => [
                'categories' => $data['labels'],
            ],
            'colors' => ['#22c55e'],
            'stroke' => [
                'curve' => 'smooth',
                'width' => 2,
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'opacityFrom' => 0.6,
                    'opacityTo' => 0.1,
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
                'formatter' => 'function(val) { return "$" + val; }',
            ],
        ];
    }
}
