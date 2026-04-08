<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\AsistenciaEntrenamiento;
use App\Models\EquipoUser;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AsistenciaReciente extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'asistenciaReciente';
    protected static ?string $heading = 'Mi Asistencia (Últimos 8 entrenamientos)';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 6;

    protected function getOptions(): array
    {
        $user = auth()->user();
        $cacheKey = "widget_asistencia_reciente_{$user->id}";

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($user) {
            $registros = AsistenciaEntrenamiento::where('user_id', $user->id)
                ->where('tenant_id', $user->tenant_id)
                ->with('entrenamiento')
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get()
                ->reverse()
                ->values();

            $labels = [];
            $presente = [];

            foreach ($registros as $reg) {
                $fecha = $reg->entrenamiento?->fecha ?? $reg->created_at;
                $labels[] = Carbon::parse($fecha)->format('d/m');
                $presente[] = $reg->presente ? 1 : 0;
            }

            return [
                'labels' => $labels,
                'presente' => $presente,
            ];
        });

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 250,
            ],
            'series' => [
                [
                    'name' => 'Asistencia',
                    'data' => $data['presente'],
                ],
            ],
            'xaxis' => [
                'categories' => $data['labels'],
            ],
            'colors' => ['#22c55e'],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                    'columnWidth' => '50%',
                    'borderRadius' => 4,
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
                'formatter' => 'function(val) { return val ? "✓" : "✗"; }',
            ],
            'yaxis' => [
                'max' => 1,
                'labels' => [
                    'formatter' => 'function(val) { return val ? "Presente" : "Ausente"; }',
                ],
            ],
        ];
    }
}
