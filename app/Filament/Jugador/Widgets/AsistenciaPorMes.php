<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\AsistenciaEntrenamiento;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class AsistenciaPorMes extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'asistenciaPorMes';
    protected static ?string $heading = 'Asistencia a entrenamientos';

    protected int | string | array $columnSpan = 'full';

   protected function getOptions(): array
{
   $data = AsistenciaEntrenamiento::selectRaw('
        EXTRACT(MONTH FROM created_at) as mes,
        SUM(CASE WHEN presente = true THEN 1 ELSE 0 END) as presentes,
        SUM(CASE WHEN presente = false THEN 1 ELSE 0 END) as ausentes
    ')
    ->groupBy('mes')
    ->get()
    ->keyBy('mes');

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
            4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
            10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        $presentes = [];
        $ausentes = [];
        $labels = [];

        foreach ($meses as $num => $nombre) {
            $labels[] = $nombre;

            $presentes[] = (int) ($data[$num]->presentes ?? 0);
            $ausentes[]  = (int) ($data[$num]->ausentes ?? 0);
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Presentes',
                    'data' => $presentes,
                ],
                [
                    'name' => 'Ausentes',
                    'data' => $ausentes,
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
            ],
            'colors' => ['#3b82f6', '#93c5fd'],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                    'columnWidth' => '50%',
                ],
            ],
        ];
    }
}