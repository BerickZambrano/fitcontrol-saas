<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\Partido;
use App\Models\EquipoUser;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;

class ProximoPartido extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 2;

    protected function getStats(): array
    {
        $user = auth()->user();

        // Obtener equipo del jugador
        $equipoUserId = EquipoUser::where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->value('equipo_id');

        if (!$equipoUserId) {
            return [
                Stat::make('Próximo Partido', 'Sin equipo asignado')
                    ->icon('heroicon-o-calendar')
                    ->color('gray'),
            ];
        }

        // Buscar próximo partido como local o visitante
        $partido = Partido::where('tenant_id', $user->tenant_id)
            ->where('fecha', '>=', Carbon::today())
            ->where(function ($q) use ($equipoUserId) {
                $q->where('equipo_local_id', $equipoUserId)
                  ->orWhere('equipo_visitante_id', $equipoUserId);
            })
            ->with(['local', 'visitante', 'torneo'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->first();

        if (!$partido) {
            return [
                Stat::make('Próximo Partido', 'No hay partidos programados')
                    ->icon('heroicon-o-calendar')
                    ->color('gray'),
            ];
        }

        $esLocal = $partido->equipo_local_id == $equipoUserId;
        $rival = $esLocal ? ($partido->visitante?->nombre ?? 'Por definir') : ($partido->local?->nombre ?? 'Por definir');
        $lugar = $esLocal ? '🏠 Local' : '✈️ Visitante';

        return [
            Stat::make('Rival', $rival)
                ->description($partido->torneo?->nombre ?? 'Amistoso')
                ->icon('heroicon-o-trophy')
                ->color('primary'),

            Stat::make('Fecha', $partido->fecha?->format('d/m/Y') ?? 'N/A')
                ->description($lugar)
                ->icon('heroicon-o-calendar')
                ->color('info'),

            Stat::make('Hora', $partido->hora ?? 'N/A')
                ->description($partido->ubicacion ?? 'Por confirmar')
                ->icon('heroicon-o-clock')
                ->color('success'),
        ];
    }
}
