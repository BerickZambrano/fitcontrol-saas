<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\Partido;
use App\Models\Entrenamiento;
use App\Models\EquipoUser;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ProximosCompromisos extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'proximosCompromisos';
    protected static ?string $heading = 'Próximos Compromisos';

    protected static ?int $sort = 10;

    protected int | string | array $columnSpan = 'full';

    protected function getOptions(): array
    {
        $user = auth()->user();
        $cacheKey = "widget_compromisos_{$user->id}";

        $data = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($user) {
            $equipoUserId = EquipoUser::where('user_id', $user->id)
                ->where('tenant_id', $user->tenant_id)
                ->value('equipo_id');

            if (!$equipoUserId) {
                return ['labels' => [], 'partidos' => [], 'entrenamientos' => []];
            }

            // Próximos partidos
            $partidos = Partido::where('tenant_id', $user->tenant_id)
                ->where('fecha', '>=', Carbon::today())
                ->where(function ($q) use ($equipoUserId) {
                    $q->where('equipo_local_id', $equipoUserId)
                      ->orWhere('equipo_visitante_id', $equipoUserId);
                })
                ->orderBy('fecha')
                ->limit(6)
                ->get();

            // Próximos entrenamientos
            $entrenamientos = Entrenamiento::where('tenant_id', $user->tenant_id)
                ->where('equipo_id', $equipoUserId)
                ->where('fecha', '>=', Carbon::today())
                ->orderBy('fecha')
                ->limit(6)
                ->get();

            // Combinar y ordenar por fecha
            $eventos = collect();

            foreach ($partidos as $p) {
                $eventos->push([
                    'fecha' => $p->fecha,
                    'tipo' => 'partido',
                    'label' => '⚽ Partido',
                ]);
            }

            foreach ($entrenamientos as $e) {
                $eventos->push([
                    'fecha' => $e->fecha,
                    'tipo' => 'entrenamiento',
                    'label' => '🏋️ Entrenamiento',
                ]);
            }

            $eventos = $eventos->sortBy('fecha')->values();

            $labels = $eventos->map(fn ($e) => Carbon::parse($e['fecha'])->format('d/m'))->toArray();
            $partidosSeries = $eventos->map(fn ($e) => $e['tipo'] === 'partido' ? 1 : 0)->toArray();
            $entrenamientosSeries = $eventos->map(fn ($e) => $e['tipo'] === 'entrenamiento' ? 1 : 0)->toArray();

            return [
                'labels' => $labels,
                'partidos' => $partidosSeries,
                'entrenamientos' => $entrenamientosSeries,
            ];
        });

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
                'stacked' => true,
            ],
            'series' => [
                [
                    'name' => 'Partidos',
                    'data' => $data['partidos'],
                ],
                [
                    'name' => 'Entrenamientos',
                    'data' => $data['entrenamientos'],
                ],
            ],
            'xaxis' => [
                'categories' => $data['labels'],
            ],
            'colors' => ['#ef4444', '#3b82f6'],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                    'columnWidth' => '50%',
                    'borderRadius' => 4,
                ],
            ],
        ];
    }
}
