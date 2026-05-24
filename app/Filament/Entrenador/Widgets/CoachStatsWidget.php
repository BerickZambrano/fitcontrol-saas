<?php

namespace App\Filament\Entrenador\Widgets;

use Filament\Widgets\Widget;
use App\Models\EquipoUser;
use Illuminate\Support\Facades\Auth;

class CoachStatsWidget extends Widget
{
    protected string $view = 'filament.entrenador.widgets.coach-stats-widget';

    protected int | string | array $columnSpan = 12;

    public function getCoachData(): array
    {
        $user = Auth::user();
        
        // Obtener equipos a su cargo
        $equiposIds = EquipoUser::where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                      ->orWhere('fecha_fin', '>=', now()->toDateString());
            })
            ->pluck('equipo_id');

        $totalEquipos = $equiposIds->count();

        // Obtener total de jugadores únicos asignados a esos mismos equipos
        $totalJugadores = 0;
        if ($totalEquipos > 0) {
            $totalJugadores = EquipoUser::whereIn('equipo_id', $equiposIds)
                ->where('user_id', '!=', $user->id) // Excluir al propio entrenador
                ->where(function ($query) {
                    $query->whereNull('fecha_fin')
                          ->orWhere('fecha_fin', '>=', now()->toDateString());
                })
                ->distinct('user_id')
                ->count();
        }

        return [
            'totalEquipos' => $totalEquipos,
            'totalJugadores' => $totalJugadores,
        ];
    }

    public function getUser()
    {
        return Auth::user();
    }
}
