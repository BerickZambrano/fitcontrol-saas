<?php

namespace App\Filament\Jugador\Pages;

use App\Models\Partido;
use App\Models\Pago;
use App\Models\HistorialMedico;
use App\Models\Rendimiento;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class MiHistorial extends Page
{
    protected static BackedEnum|string|null $navigationIcon = Heroicon::Clock;
    protected static ?string $navigationLabel = 'Mi Historial';
    protected static ?string $title = 'Mi Historial';
    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.jugador.pages.mi-historial';

    public string $activeTab = 'partidos';

    public function getTabs(): array
    {
        return [
            'partidos' => '⚽ Partidos',
            'pagos' => '💰 Pagos',
            'medico' => '🏥 Médico',
            'traspasos' => '🔄 Trayectoria',
        ];
    }

    public function getEquipos(): array
    {
        $user = auth()->user();

        return \App\Models\EquipoUser::query()
            ->withoutGlobalScopes()
            ->with(['equipo' => fn ($q) => $q->withoutGlobalScopes()])
            ->where('user_id', $user->id)
            ->orderBy('fecha_inicio', 'desc')
            ->get()
            ->map(function ($eu) {
                return [
                    'equipo' => $eu->equipo?->nombre ?? 'Equipo desconocido',
                    'fecha_inicio' => $eu->fecha_inicio ?? '—',
                    'fecha_fin' => $eu->fecha_fin ?? 'Actual',
                ];
            })
            ->toArray();
    }

    public function getTraspasos(): array
    {
        $user = auth()->user();

        return \App\Models\Traspaso::query()
            ->withoutGlobalScopes()
            ->with([
                'equipoOrigen' => fn ($q) => $q->withoutGlobalScopes(),
                'equipoDestino' => fn ($q) => $q->withoutGlobalScopes(),
            ])
            ->where('jugador_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($t) {
                return [
                    'origen' => $t->equipoOrigen?->nombre ?? 'Sin equipo',
                    'destino' => $t->equipoDestino?->nombre ?? 'Equipo desconocido',
                    'estado' => $t->estado ?? 'pendiente',
                    'fecha' => $t->created_at?->format('d/m/Y') ?? '—',
                ];
            })
            ->toArray();
    }

    public function getPartidos(): array
    {
        $user = auth()->user();

        return Rendimiento::query()
            ->withoutGlobalScopes()
            ->with([
                'partido' => fn ($q) => $q->withoutGlobalScopes(),
                'partido.torneo' => fn ($q) => $q->withoutGlobalScopes(),
            ])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($r) {
                return [
                    'partido' => $r->partido ? ($r->partido->torneo?->nombre ?? 'Amistoso') : '—',
                    'fecha' => $r->partido?->fecha ?? '—',
                    'minutos' => $r->minutos_jugados ?? 0,
                    'goles' => $r->goles ?? 0,
                    'asistencias' => $r->asistencias ?? 0,
                    'amarillas' => $r->tarjetas_amarillas ?? 0,
                    'rojas' => $r->tarjetas_rojas ?? 0,
                ];
            })
            ->toArray();
    }

    public function getPagos(): array
    {
        $user = auth()->user();

        return Pago::where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->orderBy('fecha', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($p) {
                return [
                    'monto' => $p->monto ?? 0,
                    'estado' => $p->estado ?? 'pendiente',
                    'fecha' => $p->fecha ?? '—',
                ];
            })
            ->toArray();
    }

    public function getMedico(): array
    {
        $user = auth()->user();

        return HistorialMedico::where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($h) {
                return [
                    'tipo' => $h->tipo_lesion ?? '—',
                    'gravedad' => $h->gravedad ?? '—',
                    'descripcion' => $h->descripcion ?? '—',
                    'fecha_inicio' => $h->fecha_inicio ?? '—',
                    'fecha_fin' => $h->fecha_fin ?? 'En curso',
                    'apto' => $h->apto ?? null,
                ];
            })
            ->toArray();
    }
}
