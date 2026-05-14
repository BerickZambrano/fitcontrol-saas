<?php

namespace App\Filament\Widgets;

use App\Models\AsistenciaEntrenamiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class AsistenciaPorMes extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'asistenciaPorMes';
    protected static ?string $heading = 'Asistencia a entrenamientos';

    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 6;

    protected function getOptions(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $cacheKey = $isSuperAdmin 
            ? 'widget_asistencia_12meses' 
            : "widget_asistencia_tenant_{$user->tenant_id}_12meses";

        $data = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($isSuperAdmin, $user) {
            $meses = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
                4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
                7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
                10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
            ];

            $query = AsistenciaEntrenamiento::selectRaw('
                EXTRACT(MONTH FROM created_at) as mes,
                SUM(CASE WHEN presente = true THEN 1 ELSE 0 END) as presentes,
                SUM(CASE WHEN presente = false THEN 1 ELSE 0 END) as ausentes
            ');

            // Limitar a últimos 12 meses
            $fechaLimite = Carbon::now()->subMonths(12);
            $query->where('created_at', '>=', $fechaLimite);

            if (!$isSuperAdmin) {
                $query->whereHas('entrenamiento', function ($q) use ($user) {
                    $q->where('tenant_id', $user->tenant_id);
                });
            }

            $results = $query
                ->groupBy('mes')
                ->get()
                ->keyBy('mes');

            $presentes = [];
            $ausentes = [];
            $labels = [];

            foreach ($meses as $num => $nombre) {
                $labels[] = $nombre;
                $presentes[] = (int) ($results[$num]->presentes ?? 0);
                $ausentes[] = (int) ($results[$num]->ausentes ?? 0);
            }

            return [
                'presentes' => $presentes,
                'ausentes' => $ausentes,
                'labels' => $labels,
            ];
        });

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Presentes',
                    'data' => $data['presentes'],
                ],
                [
                    'name' => 'Ausentes',
                    'data' => $data['ausentes'],
                ],
            ],
            'xaxis' => [
                'categories' => $data['labels'],
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