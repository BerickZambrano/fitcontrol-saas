<?php

namespace App\Filament\Resources\Partidos\Pages;

use App\Filament\Resources\Partidos\PartidoResource;
use App\Models\EquipoUser;
use App\Notifications\NuevoPartidoNotification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Notification;

class CreatePartido extends CreateRecord
{
    protected static string $resource = PartidoResource::class;

    protected static ?string $title = 'Crear Partido';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $partido = $this->record;

        // Obtener jugadores del equipo local
        $jugadoresLocal = EquipoUser::where('equipo_id', $partido->equipo_local_id)
            ->whereNull('fecha_fin')
            ->get();

        // Obtener jugadores del equipo visitante
        $jugadoresVisitante = EquipoUser::where('equipo_id', $partido->equipo_visitante_id)
            ->whereNull('fecha_fin')
            ->get();

        // Enviar notificación a jugadores del equipo local
        foreach ($jugadoresLocal as $equipoUser) {
            $jugador = $equipoUser->jugador;
            if ($jugador) {
                $jugador->notify(new NuevoPartidoNotification($partido));
            }
        }

        // Enviar notificación a jugadores del equipo visitante
        foreach ($jugadoresVisitante as $equipoUser) {
            $jugador = $equipoUser->jugador;
            if ($jugador) {
                $jugador->notify(new NuevoPartidoNotification($partido));
            }
        }
    }
}
