<?php

namespace App\Filament\Entrenador\Widgets;

use Filament\Widgets\Widget;
use App\Models\Entrenamiento;
use App\Models\EquipoUser;
use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;

class ProximosEntrenamientosWidget extends Widget
{
    protected string $view = 'filament.entrenador.widgets.proximos-entrenamientos-widget';

    protected int | string | array $columnSpan = 6;

    protected static ?int $sort = 2;

    public function getEntrenamientos()
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

        return Entrenamiento::whereIn('equipo_id', $equiposIds)
            ->where('fecha', '>=', now()->toDateString())
            ->with(['equipo', 'instalacion'])
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->limit(5)
            ->get();
    }
}
