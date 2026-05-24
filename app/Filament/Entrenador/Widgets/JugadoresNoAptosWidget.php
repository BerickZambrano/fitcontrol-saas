<?php

namespace App\Filament\Entrenador\Widgets;

use Filament\Widgets\Widget;
use App\Models\HistorialMedico;
use App\Models\EquipoUser;
use App\Models\Equipo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class JugadoresNoAptosWidget extends Widget
{
    protected string $view = 'filament.entrenador.widgets.jugadores-no-aptos-widget';

    protected int | string | array $columnSpan = 12;

    protected static ?int $sort = 6;

    public function getJugadoresNoAptos()
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

        // Obtener IDs de los jugadores asignados a esos equipos
        $jugadoresIds = EquipoUser::whereIn('equipo_id', $equiposIds)
            ->where('user_id', '!=', $user->id) // Excluir al entrenador
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                      ->orWhere('fecha_fin', '>=', now()->toDateString());
            })
            ->pluck('user_id')
            ->distinct();

        if ($jugadoresIds->isEmpty()) {
            return collect();
        }

        return HistorialMedico::whereIn('user_id', $jugadoresIds)
            ->where('apto', false)
            ->where('fecha_inicio', '<=', now()->toDateString())
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                      ->orWhere('fecha_fin', '>=', now()->toDateString());
            })
            ->with(['usuario'])
            ->orderBy('fecha_inicio', 'desc')
            ->limit(5)
            ->get();
    }
}
