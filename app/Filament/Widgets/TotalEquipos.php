<?php

namespace App\Filament\Widgets;

use App\Models\Equipo;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class TotalEquipos extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'totalEquiposChart';
    protected static ?string $heading = 'Equipos por Categoría';

    protected int | string | array $columnSpan = 1;

    protected function getOptions(): array
    {
        $query = Equipo::query();

        // 🔐 Multi-tenant
        if (!auth()->user()->hasRole('super_admin')) {
            $query->where('tenant_id', auth()->user()->tenant_id);
        }

        // 🔥 Conteo real según tu ENUM
        $profesional = (clone $query)->where('categoria', 'profesional')->count();
        $amateur = (clone $query)->where('categoria', 'amateur')->count();
        $formativo = (clone $query)->where('categoria', 'formativo')->count();

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 320,
            ],

            'series' => [
                $profesional ?? 0,
                $amateur ?? 0,
                $formativo ?? 0,
            ],

            'labels' => [
                'Profesional',
                'Amateur',
                'Formativo',
            ],

            'colors' => [
                '#2563eb',
                '#3b82f6',
                '#93c5fd',
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