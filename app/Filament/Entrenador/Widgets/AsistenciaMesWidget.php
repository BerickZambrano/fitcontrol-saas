<?php

namespace App\Filament\Entrenador\Widgets;

use Filament\Widgets\Widget;
use App\Models\Entrenamiento;
use App\Models\AsistenciaEntrenamiento;
use App\Models\EquipoUser;
use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AsistenciaMesWidget extends Widget
{
    protected string $view = 'filament.entrenador.widgets.asistencia-mes-widget';

    protected int | string | array $columnSpan = 6;

    protected static ?int $sort = 4;

    public function getAsistenciaEquipos()
    {
        $user = Auth::user();

        if ($user->hasRole(['super_admin', 'Administrador'])) {
            $equipos = Equipo::all();
        } else {
            $equiposIds = EquipoUser::where('user_id', $user->id)
                ->where(function ($query) {
                    $query->whereNull('fecha_fin')
                          ->orWhere('fecha_fin', '>=', now()->toDateString());
                })
                ->pluck('equipo_id');
            $equipos = Equipo::whereIn('id', $equiposIds)->get();
        }

        $currentMonthStart = Carbon::now()->startOfMonth()->toDateString();
        $currentMonthEnd = Carbon::now()->endOfMonth()->toDateString();

        $result = collect();

        foreach ($equipos as $eq) {
            $trainingsThisMonth = Entrenamiento::where('equipo_id', $eq->id)
                ->whereBetween('fecha', [$currentMonthStart, $currentMonthEnd])
                ->pluck('id');

            if ($trainingsThisMonth->isEmpty()) {
                $result->push([
                    'equipo' => $eq->nombre,
                    'tasa' => null,
                    'sesiones' => 0,
                ]);
                continue;
            }

            $totalAsistencias = AsistenciaEntrenamiento::whereIn('entrenamiento_id', $trainingsThisMonth)->count();
            $presentes = AsistenciaEntrenamiento::whereIn('entrenamiento_id', $trainingsThisMonth)->where('presente', true)->count();

            $rate = $totalAsistencias > 0 ? round(($presentes / $totalAsistencias) * 100) : null;

            $result->push([
                'equipo' => $eq->nombre,
                'tasa' => $rate,
                'sesiones' => $trainingsThisMonth->count(),
            ]);
        }

        return $result;
    }
}
