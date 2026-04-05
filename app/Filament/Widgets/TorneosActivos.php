<?php

namespace App\Filament\Widgets;

use App\Models\Torneo;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class TorneosActivos extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'torneosChart';
    protected static ?string $heading = 'Torneos';

    protected array|string|int $columnSpan = [
        'default' => 1,
        'md' => 2,
        'xl' => 2,
    ];

    protected function getOptions(): array
    {
        $query = Torneo::query();

        if (!auth()->user()->hasRole('super_admin')) {
            $query->where('tenant_id', auth()->user()->tenant_id);
        }

        $activos = (clone $query)->where('estado', 'activo')->count();
        $finalizados = (clone $query)->where('estado', 'finalizado')->count();
        $progreso = (clone $query)->where('estado', 'en_progreso')->count();

        return [
            'chart' => [
                'type' => 'pie',
                'height' => 300,
            ],

            'series' => [
                $activos ?? 0,
                $finalizados ?? 0,
                $progreso ?? 0,
            ],

            'labels' => [
                'Activos',
                'Finalizados',
                'En progreso',
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
        ];
    }
}