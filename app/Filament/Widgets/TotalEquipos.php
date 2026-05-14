<?php

namespace App\Filament\Widgets;

use App\Models\Equipo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class TotalEquipos extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'totalEquiposChart';
    protected static ?string $heading = 'Equipos por Categoría';

    protected int | string | array $columnSpan = 4;
    protected static ?int $sort = 3;

    protected function getOptions(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $cacheKey = $isSuperAdmin 
            ? 'widget_total_equipos_super_admin' 
            : "widget_total_equipos_tenant_{$user->tenant_id}";

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($isSuperAdmin, $user) {
            $query = Equipo::query();

            if (!$isSuperAdmin) {
                $query->where('tenant_id', $user->tenant_id);
            }

            $results = $query
                ->select('categoria', DB::raw('COUNT(*) as total'))
                ->groupBy('categoria')
                ->pluck('total', 'categoria');

            return [
                'profesional' => (int) ($results['profesional'] ?? 0),
                'amateur' => (int) ($results['amateur'] ?? 0),
                'formativo' => (int) ($results['formativo'] ?? 0),
            ];
        });

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 320,
            ],

            'series' => [
                $data['profesional'],
                $data['amateur'],
                $data['formativo'],
            ],

            'labels' => [
                'Profesional',
                'Amateur',
                'Formativo',
            ],

            'colors' => [
                auth()->user()->tenant?->colores_oficiales['primary'] ?? '#3b82f6',
                '#64748b',
                '#94a3b8',
            ],

            'legend' => [
                'position' => 'bottom',
            ],

            'dataLabels' => [
                'enabled' => true,
            ],

            // ✅ SIN formatter (evita error Livewire)
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '65%',
                    ],
                ],
            ],
        ];
    }
}