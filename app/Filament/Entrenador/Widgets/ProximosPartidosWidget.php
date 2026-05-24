<?php

namespace App\Filament\Entrenador\Widgets;

use Filament\Widgets\Widget;
use App\Models\Partido;
use App\Models\EquipoUser;
use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;

class ProximosPartidosWidget extends Widget
{
    protected string $view = 'filament.entrenador.widgets.proximos-partidos-widget';

    protected int | string | array $columnSpan = 6;

    protected static ?int $sort = 3;

    public function getPartidos()
    {
        $user = Auth::user();

        if ($user->hasRole(['super_admin', 'Administrador'])) {
            $equiposIds = Equipo::pluck('id');
        } else {
            $equiposIds = EquipoUser::where('user_id', $user->id)
                ->where(function ($query) {
                    $query->whereNull('fecha_fin')
                          ->orWhere('fecha_fin', '>=', now()->toDateString());
                })
                ->pluck('equipo_id');
        }

        if ($equiposIds->isEmpty()) {
            return collect();
        }

        return Partido::where(function ($query) use ($equiposIds) {
                $query->whereIn('equipo_local_id', $equiposIds)
                      ->orWhereIn('equipo_visitante_id', $equiposIds);
            })
            ->where('fecha', '>=', now()->toDateString())
            ->with(['local', 'visitante', 'torneo', 'instalacion'])
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->limit(5)
            ->get();
    }
}
