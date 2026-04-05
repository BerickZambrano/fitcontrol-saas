<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class TotalUsuarios extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'totalUsuariosChart';
    protected static ?string $heading = 'Usuarios por Rol';

    protected int | string | array $columnSpan = 1;

    protected function getOptions(): array
    {
        $query = User::query();

        //para que filtre por tenant en caso de no ser super_admin
        if (auth()->check() && auth()->user()->hasRole('super_admin')) {
            $baseQuery = $query;
        } else {
            $baseQuery = $query->where('tenant_id', auth()->user()->tenant_id);
        }

        //cuenta los usuarios que tiene en la bd y suma 
        $jugadores = (clone $baseQuery)
            ->whereHas('roles', fn($q) => $q->where('name', 'Jugador'))
            ->count();

        $entrenadores = (clone $baseQuery)
            ->whereHas('roles', fn($q) => $q->where('name', 'Entrenador'))
            ->count();

        $admins = (clone $baseQuery)
            ->whereHas('roles', fn($q) => $q->where('name', 'Administrador'))
            ->count();

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 320,
            ],

            'series' => [
                $jugadores ?? 0,
                $entrenadores ?? 0,
                $admins ?? 0,
            ],

            'labels' => [
                'Jugadores',
                'Entrenadores',
                'Admins',
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