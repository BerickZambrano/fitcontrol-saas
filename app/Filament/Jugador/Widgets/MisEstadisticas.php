<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\Rendimiento;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Illuminate\Support\Facades\Cache;

class MisEstadisticas extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'misEstadisticas';
    protected static ?string $heading = 'Mis Estadísticas de Temporada';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 6;

    protected function getOptions(): array
    {
        $user = auth()->user();
        $cacheKey = "widget_estadisticas_jugador_{$user->id}";

        $data = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($user) {
            $stats = Rendimiento::where('user_id', $user->id)
                ->where('tenant_id', $user->tenant_id)
                ->selectRaw('
                    SUM(minutos_jugados) as total_minutos,
                    SUM(goles) as total_goles,
                    SUM(asistencias) as total_asistencias,
                    SUM(tarjetas_amarillas) as total_amarillas,
                    SUM(tarjetas_rojas) as total_rojas,
                    COUNT(*) as partidos_jugados
                ')
                ->first();

            return [
                'minutos' => (int) ($stats->total_minutos ?? 0),
                'goles' => (int) ($stats->total_goles ?? 0),
                'asistencias' => (int) ($stats->total_asistencias ?? 0),
                'amarillas' => (int) ($stats->total_amarillas ?? 0),
                'rojas' => (int) ($stats->total_rojas ?? 0),
                'partidos' => (int) ($stats->partidos_jugados ?? 0),
            ];
        });

        return [
            'chart' => [
                'type' => 'radar',
                'height' => 350,
            ],
            'series' => [
                [
                    'name' => 'Estadísticas',
                    'data' => [
                        $data['goles'],
                        $data['asistencias'],
                        min($data['minutos'] / 10, 10), // Normalizar minutos
                        $data['partidos'],
                        max(0, 10 - $data['amarillas'] - $data['rojas']), // Disciplina
                    ],
                ],
            ],
            'xaxis' => [
                'categories' => ['Goles', 'Asistencias', 'Minutos (÷10)', 'Partidos', 'Disciplina'],
            ],
            'colors' => ['#3b82f6'],
            'stroke' => [
                'width' => 2,
            ],
            'fill' => [
                'opacity' => 0.2,
            ],
            'markers' => [
                'size' => 4,
            ],
        ];
    }
}
