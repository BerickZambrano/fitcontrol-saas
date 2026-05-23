<?php

namespace App\Filament\Admin\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use App\Models\Entrenamiento;
use App\Models\Partido;
use UnitEnum;

class Calendario extends Page
{
    protected static BackedEnum|string|null $navigationIcon = Heroicon::Calendar;
    protected static ?string $navigationLabel = 'Calendario';
    protected static string|UnitEnum|null $navigationGroup = 'Panel Principal';
    protected static ?string $title = 'Calendario';

    protected string $view = 'filament.pages.calendario';

    public function getEvents(): array
    {
        $tenantId = auth()->user()->tenant_id;
        $isSuperAdmin = auth()->user()->hasRole('super_admin');

        $entrenamientosQuery = Entrenamiento::query();
        $partidosQuery = Partido::query();

        if (!$isSuperAdmin) {
            $entrenamientosQuery->where('tenant_id', $tenantId);
            $partidosQuery->where('tenant_id', $tenantId);
        }

        $entrenamientos = $entrenamientosQuery->with('equipo')->get()->map(fn ($e) => [
            'title' => $e->nombre,
            'start' => $e->fecha . ($e->hora ? 'T' . $e->hora : ''),
            'color' => '#2563eb',
            'type' => 'Entrenamiento',
            'location' => $e->ubicacion ?? 'No especificada',
            'time' => $e->hora ? date('g:i A', strtotime($e->hora)) : 'No especificada',
            'extra' => 'Equipo: ' . ($e->equipo?->nombre ?? 'General'),
        ]);

        $partidos = $partidosQuery->with(['local', 'visitante', 'torneo'])->get()->map(fn ($p) => [
            'title' => ($p->local?->nombre ?? 'Local') . ' vs ' . ($p->visitante?->nombre ?? 'Visitante'),
            'start' => $p->fecha . ($p->hora ? 'T' . $p->hora : ''),
            'color' => '#16a34a',
            'type' => 'Partido',
            'location' => 'Estadio / Cancha asignada',
            'time' => $p->hora ? date('g:i A', strtotime($p->hora)) : 'No especificada',
            'extra' => ($p->torneo?->nombre ? 'Torneo: ' . $p->torneo->nombre : 'Partido amistoso') . ' - Resultado: ' . ($p->resultado ?? 'Pendiente'),
        ]);

        return $entrenamientos
            ->merge($partidos)
            ->values()
            ->toArray();
    }
}