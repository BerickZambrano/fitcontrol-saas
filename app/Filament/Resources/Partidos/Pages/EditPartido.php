<?php

namespace App\Filament\Resources\Partidos\Pages;

use App\Filament\Resources\Partidos\PartidoResource;
use App\Models\Convocatoria;
use App\Models\EquipoUser;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPartido extends EditRecord
{
    protected static string $resource = PartidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $partido = $this->record;
        $jugadoresConvocadosIds = $this->data['jugadores_convocados'] ?? [];

        if (empty($jugadoresConvocadosIds)) {
            return;
        }

        $fecha = $partido->fecha ? \Carbon\Carbon::parse($partido->fecha)->format('d/m/Y') : 'Sin fecha';
        $hora  = $partido->hora  ? \Carbon\Carbon::parse($partido->hora)->format('H:i')   : 'Sin hora';

        $equipoLocalNombre    = $partido->equipo_local_id
            ? \App\Models\Equipo::find($partido->equipo_local_id)?->nombre ?? 'Equipo local'
            : 'Equipo local';
        $equipoVisitanteNombre = $partido->equipo_visitante_id
            ? \App\Models\Equipo::find($partido->equipo_visitante_id)?->nombre ?? 'Equipo visitante'
            : 'Equipo visitante';

        $title = 'Partido: ' . $equipoLocalNombre . ' vs ' . $equipoVisitanteNombre;

        foreach ($jugadoresConvocadosIds as $jugadorId) {
            // Evitar duplicados: si ya existe convocatoria para este jugador y partido, se omite
            $yaConvocado = Convocatoria::where('partido_id', $partido->id)
                ->where('jugador_id', $jugadorId)
                ->exists();

            if ($yaConvocado) {
                continue;
            }

            // Obtener el equipo al que pertenece el jugador
            $equipoId = EquipoUser::where('user_id', $jugadorId)
                ->where(function ($query) {
                    $query->whereNull('fecha_fin')
                          ->orWhere('fecha_fin', '>=', now()->toDateString());
                })
                ->value('equipo_id') ?? $partido->equipo_local_id;

            // Crear la convocatoria
            Convocatoria::create([
                'partido_id'        => $partido->id,
                'jugador_id'        => $jugadorId,
                'equipo_id'         => $equipoId,
                'estado_asistencia' => 'convocado',
            ]);

            // Notificar al jugador
            $jugador = \App\Models\User::find($jugadorId);
            if ($jugador) {
                \Filament\Notifications\Notification::make()
                    ->title($title)
                    ->body("📅 {$fecha} ⏰ {$hora}")
                    ->icon('heroicon-o-trophy')
                    ->color('primary')
                    ->sendToDatabase($jugador);
            }
        }
    }
}
