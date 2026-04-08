<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\Entrenamiento;
use App\Models\EquipoUser;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;

class ProximoEntrenamiento extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 4;

    protected function getStats(): array
    {
        $user = auth()->user();

        // Obtener equipo del jugador
        $equipoUserId = EquipoUser::where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->value('equipo_id');

        if (!$equipoUserId) {
            return [
                Stat::make('Próximo Entrenamiento', 'Sin equipo asignado')
                    ->icon('heroicon-o-arrow-trending-up')
                    ->color('gray'),
            ];
        }

        // Buscar próximo entrenamiento del equipo
        $entrenamiento = Entrenamiento::where('tenant_id', $user->tenant_id)
            ->where('equipo_id', $equipoUserId)
            ->where('fecha', '>=', Carbon::today())
            ->orderBy('fecha')
            ->orderBy('hora')
            ->first();

        if (!$entrenamiento) {
            return [
                Stat::make('Próximo Entrenamiento', 'No hay entrenamientos programados')
                    ->icon('heroicon-o-arrow-trending-up')
                    ->color('gray'),
            ];
        }

        $diasRestantes = Carbon::today()->diffInDays($entrenamiento->fecha, false);
        $textoDias = $diasRestantes == 0 ? 'Hoy' : ($diasRestantes == 1 ? 'Mañana' : "En {$diasRestantes} días");

        return [
            Stat::make('Próximo Entrenamiento', $entrenamiento->nombre ?? 'Entrenamiento')
                ->description($textoDias)
                ->icon('heroicon-o-arrow-trending-up')
                ->color('success'),

            Stat::make('Fecha', $entrenamiento->fecha?->format('d/m/Y') ?? 'N/A')
                ->icon('heroicon-o-calendar')
                ->color('info'),

            Stat::make('Hora', $entrenamiento->hora ?? 'N/A')
                ->description($entrenamiento->ubicacion ?? 'Sin ubicación')
                ->icon('heroicon-o-map-pin')
                ->color('warning'),
        ];
    }
}
