<?php

namespace App\Filament\Resources\Partidos\Pages;

use App\Filament\Resources\Partidos\PartidoResource;
use App\Models\EquipoUser;
use App\Notifications\NuevoPartidoNotification;
use Filament\Resources\Pages\CreateRecord;

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

        $jugadoresLocal = EquipoUser::where('equipo_id', $partido->equipo_local_id)
            ->whereNull('fecha_fin')
            ->get();

        $jugadoresVisitante = EquipoUser::where('equipo_id', $partido->equipo_visitante_id)
            ->whereNull('fecha_fin')
            ->get();

        foreach ($jugadoresLocal as $equipoUser) {
            $jugador = $equipoUser->jugador;
            if ($jugador) {
                $jugador->notify(new NuevoPartidoNotification($partido));
            }
        }

        foreach ($jugadoresVisitante as $equipoUser) {
            $jugador = $equipoUser->jugador;
            if ($jugador) {
                $jugador->notify(new NuevoPartidoNotification($partido));
            }
        }
    }
}
