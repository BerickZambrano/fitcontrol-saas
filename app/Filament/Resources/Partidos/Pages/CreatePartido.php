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
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                      ->orWhere('fecha_fin', '>=', now()->toDateString());
            })
            ->get();

        $jugadoresVisitante = EquipoUser::where('equipo_id', $partido->equipo_visitante_id)
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                      ->orWhere('fecha_fin', '>=', now()->toDateString());
            })
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
