<?php

namespace App\Filament\Widgets;

use App\Models\Torneo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class TorneosActivos extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'torneosChart';
    protected static ?string $heading = 'Torneos';

    protected array|string|int $columnSpan = 6;
    protected static ?int $sort = 8;

    protected function getOptions(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $cacheKey = $isSuperAdmin 
            ? 'widget_torneos_super_admin' 
            : "widget_torneos_tenant_{$user->tenant_id}";

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($isSuperAdmin, $user) {
            $query = Torneo::query();

            if (!$isSuperAdmin) {
                $query->where('tenant_id', $user->tenant_id);
            }

            $results = $query
                ->select('estado', DB::raw('COUNT(*) as total'))
                ->groupBy('estado')
                ->pluck('total', 'estado');

            return [
                'activo' => (int) ($results['activo'] ?? 0),
                'finalizado' => (int) ($results['finalizado'] ?? 0),
                'en_progreso' => (int) ($results['en_progreso'] ?? 0),
            ];
        });

        return [
            'chart' => [
                'type' => 'pie',
                'height' => 300,
            ],

            'series' => [
                $data['activo'],
                $data['finalizado'],
                $data['en_progreso'],
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