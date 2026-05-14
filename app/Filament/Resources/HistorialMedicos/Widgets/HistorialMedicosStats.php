<?php

namespace App\Filament\Resources\HistorialMedicos\Widgets;

use App\Models\HistorialMedico;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HistorialMedicosStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user && $user->hasRole('super_admin');

        $queryLesiones = HistorialMedico::query();
        $queryEnfermedades = HistorialMedico::query();
        $queryNoAptos = HistorialMedico::query();

        if (!$isSuperAdmin && $user && $user->tenant_id) {
            $queryLesiones->where('tenant_id', $user->tenant_id);
            $queryEnfermedades->where('tenant_id', $user->tenant_id);
            $queryNoAptos->where('tenant_id', $user->tenant_id);
        }

        $cantLesiones = $queryLesiones->where('tipo_lesion', 'lesion')->count();
        $cantEnfermedades = $queryEnfermedades->where('tipo_lesion', 'enfermedad')->count();
        $cantNoAptos = $queryNoAptos->where('apto', false)->count();

        return [
            Stat::make('Lesiones Registradas', $cantLesiones)
                ->description('Total de casos de lesiones')
                ->icon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Enfermedades Registradas', $cantEnfermedades)
                ->description('Total de casos de enfermedad')
                ->icon('heroicon-m-heart')
                ->color('warning'),

            Stat::make('Jugadores No Aptos', $cantNoAptos)
                ->description('Jugadores actualmente no aptos')
                ->icon('heroicon-m-user-minus')
                ->color('info'),
        ];
    }
}
