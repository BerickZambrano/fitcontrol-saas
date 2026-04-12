<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\Entrenamiento;
use App\Models\Partido;
use App\Models\EquipoUser;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class ProximoCompromiso extends Widget
{
    use HasWidgetShield;

    protected string $view = 'filament.jugador.widgets.proximo-compromiso';

    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    public function getNextEvent(): ?array
    {
        $user = auth()->user();

        // Get player's team
        $equipoId = EquipoUser::where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->value('equipo_id');

        if (!$equipoId) {
            return null;
        }

        $hoy = Carbon::today();

        // Next training
        $entrenamiento = Entrenamiento::where('tenant_id', $user->tenant_id)
            ->where('equipo_id', $equipoId)
            ->where('fecha', '>=', $hoy)
            ->orderBy('fecha')
            ->orderBy('hora')
            ->first();

        // Next match
        $partido = Partido::where('tenant_id', $user->tenant_id)
            ->where(function ($q) use ($equipoId) {
                $q->where('equipo_local_id', $equipoId)
                  ->orWhere('equipo_visitante_id', $equipoId);
            })
            ->where('fecha', '>=', $hoy)
            ->orderBy('fecha')
            ->orderBy('hora')
            ->first();

        // Return whichever is sooner
        $events = [];

        if ($entrenamiento) {
            $events[] = [
                'tipo' => 'Entrenamiento',
                'nombre' => $entrenamiento->nombre,
                'fecha' => $entrenamiento->fecha,
                'hora' => $entrenamiento->hora,
                'ubicacion' => $entrenamiento->ubicacion,
                'equipo' => $entrenamiento->equipo?->nombre ?? '',
                'color' => '#2563eb',
            ];
        }

        if ($partido) {
            $rival = $partido->equipoLocalId == $equipoId
                ? $partido->visitante?->nombre ?? '?'
                : $partido->local?->nombre ?? '?';

            $events[] = [
                'tipo' => 'Partido',
                'nombre' => 'vs ' . $rival,
                'fecha' => $partido->fecha,
                'hora' => $partido->hora ?? '—',
                'ubicacion' => '—',
                'equipo' => $partido->torneo?->nombre ?? '',
                'color' => '#16a34a',
            ];
        }

        if (empty($events)) {
            return null;
        }

        usort($events, function ($a, $b) {
            return strtotime($a['fecha']) <=> strtotime($b['fecha']);
        });

        return $events[0];
    }
}
