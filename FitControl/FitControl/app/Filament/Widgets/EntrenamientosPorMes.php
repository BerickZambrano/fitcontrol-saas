<?php

namespace App\Filament\Widgets;

use App\Models\Entrenamiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class EntrenamientosPorMes extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'entrenamientosPorMes';
    protected static ?string $heading = 'Entrenamientos por mes';

    protected int | string | array $columnSpan = 6;
    protected static ?int $sort = 7;

    protected function getOptions(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $cacheKey = $isSuperAdmin 
            ? 'widget_entrenamientos_12meses' 
            : "widget_entrenamientos_tenant_{$user->tenant_id}_12meses";

        $data = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($isSuperAdmin, $user) {
            $query = Entrenamiento::selectRaw("
                TO_CHAR(fecha, 'YYYY-MM') as periodo,
                COUNT(*) as total
            ");

            // Limitar a últimos 12 meses
            $fechaLimite = Carbon::now()->subMonths(12);
            $query->where('fecha', '>=', $fechaLimite);

            if (!$isSuperAdmin) {
                $query->where('tenant_id', $user->tenant_id);
            }

            $results = $query
                ->groupBy('periodo')
                ->orderBy('periodo')
                ->get();

            $labels = $results->pluck('periodo')->map(fn ($p) => $this->formatearPeriodo($p))->toArray();
            $series = $results->pluck('total')->map(fn ($v) => (int) $v)->toArray();

            return [
                'labels' => $labels,
                'series' => $series,
            ];
        });

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Entrenamientos',
                    'data' => $data['series'],
                ],
            ],
            'xaxis' => [
                'categories' => $data['labels'],
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