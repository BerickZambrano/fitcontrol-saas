<?php

namespace App\Filament\Widgets;

use App\Models\HistorialMedico;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class JugadoresNoAptos extends ApexChartWidget
{
    protected static ?string $heading = 'Estado de jugadores';

    protected int | string | array $columnSpan = 4;
    protected static ?int $sort = 5;

    protected function getOptions(): array
    {
        $cacheKey = 'widget_jugadores_estado';

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () {
            $results = HistorialMedico::selectRaw('
                apto,
                COUNT(DISTINCT user_id) as total
            ')
            ->groupBy('apto')
            ->pluck('total', 'apto');

            return [
                'aptos' => (int) ($results[true] ?? 0),
                'no_aptos' => (int) ($results[false] ?? 0),
            ];
        });

        return [
            'chart' => [
                'type' => 'donut',
            ],
            'colors' => [
                '#2563eb',
                '#3b82f6',
                '#93c5fd',
            ],
            'series' => [$data['aptos'], $data['no_aptos']],
            'labels' => ['Aptos', 'No aptos'],
        ];
    }
}