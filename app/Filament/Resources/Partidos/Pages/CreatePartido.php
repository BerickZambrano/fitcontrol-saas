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
            ->where('user_id', '!=', auth()->id())
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                      ->orWhere('fecha_fin', '>=', now()->toDateString());
            })
            ->get();

        $jugadoresVisitante = EquipoUser::where('equipo_id', $partido->equipo_visitante_id)
            ->where('user_id', '!=', auth()->id())
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                      ->orWhere('fecha_fin', '>=', now()->toDateString());
            })
            ->get();

        $fecha = $partido->fecha ? \Carbon\Carbon::parse($partido->fecha)->format('d/m/Y') : 'Sin fecha';
        $hora = $partido->hora ? \Carbon\Carbon::parse($partido->hora)->format('H:i') : 'Sin hora';
        
        $equipoLocalNombre = $partido->equipo_local_id 
            ? \App\Models\Equipo::find($partido->equipo_local_id)?->nombre ?? 'Equipo local' 
            : 'Equipo local';
        $equipoVisitanteNombre = $partido->equipo_visitante_id 
            ? \App\Models\Equipo::find($partido->equipo_visitante_id)?->nombre ?? 'Equipo visitante' 
            : 'Equipo visitante';

        $title = 'Nuevo partido: ' . $equipoLocalNombre . ' vs ' . $equipoVisitanteNombre;

        foreach ($jugadoresLocal as $equipoUser) {
            $jugador = $equipoUser->jugador;
            if ($jugador) {
                \Filament\Notifications\Notification::make()
                    ->title($title)
                    ->body("📅 {$fecha} ⏰ {$hora}")
                    ->icon('heroicon-o-trophy')
                    ->color('primary')
                    ->viewData([
                        'type' => 'partido',
                        'partido_id' => $partido->id,
                        'equipo_id' => $partido->equipo_local_id,
                    ])
                    ->sendToDatabase($jugador);
            }
        }

        foreach ($jugadoresVisitante as $equipoUser) {
            $jugador = $equipoUser->jugador;
            if ($jugador) {
                \Filament\Notifications\Notification::make()
                    ->title($title)
                    ->body("📅 {$fecha} ⏰ {$hora}")
                    ->icon('heroicon-o-trophy')
                    ->color('primary')
                    ->viewData([
                        'type' => 'partido',
                        'partido_id' => $partido->id,
                        'equipo_id' => $partido->equipo_visitante_id,
                    ])
                    ->sendToDatabase($jugador);
            }
        }
    }
}
