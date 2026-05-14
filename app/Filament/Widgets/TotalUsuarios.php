<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class TotalUsuarios extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'totalUsuariosChart';
    protected static ?string $heading = 'Usuarios por Rol';

    protected int | string | array $columnSpan = 4;
    protected static ?int $sort = 4;

    protected function getOptions(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $cacheKey = $isSuperAdmin 
            ? 'widget_total_usuarios_super_admin' 
            : "widget_total_usuarios_tenant_{$user->tenant_id}";

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($isSuperAdmin, $user) {
            $query = User::query()
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id');

            if (!$isSuperAdmin) {
                $query->where('users.tenant_id', $user->tenant_id);
            }

            $results = $query
                ->select('roles.name', DB::raw('COUNT(DISTINCT users.id) as total'))
                ->groupBy('roles.name')
                ->pluck('total', 'roles.name');

            return [
                'Jugador' => (int) ($results['Jugador'] ?? 0),
                'Entrenador' => (int) ($results['Entrenador'] ?? 0),
                'Administrador' => (int) ($results['Administrador'] ?? 0),
            ];
        });

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 320,
            ],

            'series' => [
                $data['Jugador'],
                $data['Entrenador'],
                $data['Administrador'],
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