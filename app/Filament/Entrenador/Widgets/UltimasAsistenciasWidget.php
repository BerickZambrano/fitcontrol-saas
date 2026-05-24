<?php

namespace App\Filament\Entrenador\Widgets;

use Filament\Widgets\Widget;
use App\Models\Entrenamiento;
use App\Models\EquipoUser;
use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;

class UltimasAsistenciasWidget extends Widget
{
    protected string $view = 'filament.entrenador.widgets.ultimas-asistencias-widget';

    protected int | string | array $columnSpan = 6;

    protected static ?int $sort = 5;

    public function getUltimasAsistencias()
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

        $trainings = Entrenamiento::whereIn('equipo_id', $equiposIds)
            ->whereHas('asistencias')
            ->with(['equipo', 'asistencias'])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->limit(5)
            ->get();

        return $trainings->map(function ($t) {
            $total = $t->asistencias->count();
            $presentes = $t->asistencias->where('presente', true)->count();
            $porcentaje = $total > 0 ? round(($presentes / $total) * 100) : 0;

            return [
                'id' => $t->id,
                'nombre' => $t->nombre,
                'equipo' => $t->equipo?->nombre ?? 'Sin equipo',
                'fecha' => $t->fecha,
                'total' => $total,
                'presentes' => $presentes,
                'porcentaje' => $porcentaje,
            ];
        });
    }
}
