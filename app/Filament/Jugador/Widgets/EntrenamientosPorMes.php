<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\Entrenamiento;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class EntrenamientosPorMes extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'entrenamientosPorMes';
    protected static ?string $heading = 'Entrenamientos por mes';

    protected int | string | array $columnSpan = 2;

    protected function getOptions(): array
    {
        $data = Entrenamiento::selectRaw("
        TO_CHAR(fecha, 'YYYY-MM') as periodo,
        COUNT(*) as total
    ")
    ->groupBy('periodo')
    ->orderBy('periodo')
    ->get();

        $labels = $data->pluck('periodo')->map(fn ($p) => $this->formatearPeriodo($p))->toArray();
        $series = $data->pluck('total')->map(fn ($v) => (int) $v)->toArray();

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Entrenamientos',
                    'data' => $series,
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
            ],
            'stroke' => [
                'curve' => 'smooth', 
            ],
            'colors' => ['#2563eb'],
        ];
    }

    private function formatearPeriodo(string $periodo): string
    {
        [$anio, $mes] = explode('-', $periodo);

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
            4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
            10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        return $meses[(int) $mes] . ' ' . $anio;
    }
}