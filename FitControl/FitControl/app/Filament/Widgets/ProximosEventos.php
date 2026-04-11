<?php

namespace App\Filament\Widgets;

use App\Models\Entrenamiento;
use App\Models\Partido;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class ProximosEventos extends Widget
{
    protected string $view = 'filament.widgets.proximos-eventos';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = null;

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return auth()->check();
    }

    public function getEvents(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $tenantId = $user->tenant_id;
        $hoy = Carbon::today();
        $proximaSemana = Carbon::today()->addDays(7);

        // Entrenamientos
        $entrenamientosData = Entrenamiento::query()
            ->with('equipo')
            ->whereBetween('fecha', [$hoy, $proximaSemana])
            ->when(!$isSuperAdmin, fn ($q) => $q->where('tenant_id', $tenantId))
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get()
            ->map(fn ($e) => [
                'tipo' => 'Entrenamiento',
                'nombre' => $e->nombre,
                'fecha' => $e->fecha,
                'hora' => $e->hora,
                'equipo' => $e->equipo?->nombre ?? '—',
                'color' => '#2563eb',
            ])->values()->all();

        // Partidos
        $partidosData = Partido::query()
            ->with(['local', 'visitante', 'torneo'])
            ->whereBetween('fecha', [$hoy, $proximaSemana])
            ->when(!$isSuperAdmin, fn ($q) => $q->where('tenant_id', $tenantId))
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get()
            ->map(fn ($p) => [
                'tipo' => 'Partido',
                'nombre' => ($p->local?->nombre ?? '?') . ' vs ' . ($p->visitante?->nombre ?? '?'),
                'fecha' => $p->fecha,
                'hora' => $p->hora ?? '—',
                'equipo' => $p->torneo?->nombre ?? '—',
                'color' => '#16a34a',
            ])->values()->all();

        $eventos = collect(array_merge($entrenamientosData, $partidosData))
            ->sortBy('fecha')
            ->values()
            ->all();

        return $eventos;
    }
}
