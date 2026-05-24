<?php

namespace App\Filament\Resources\Entrenamientos\Pages;

use App\Filament\Resources\Entrenamientos\EntrenamientoResource;
use App\Models\EquipoUser;
use App\Notifications\NuevoEntrenamientoNotification;
use Filament\Resources\Pages\CreateRecord;

class CreateEntrenamiento extends CreateRecord
{
    protected static string $resource = EntrenamientoResource::class;

    protected static ?string $title = 'Crear Entrenamiento';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $entrenamiento = $this->record;

        $jugadores = EquipoUser::where('equipo_id', $entrenamiento->equipo_id)
            ->where('user_id', '!=', auth()->id())
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                      ->orWhere('fecha_fin', '>=', now()->toDateString());
            })
            ->get();

        $fecha = $entrenamiento->fecha ? \Carbon\Carbon::parse($entrenamiento->fecha)->format('d/m/Y') : 'Sin fecha';
        $hora = $entrenamiento->hora ? \Carbon\Carbon::parse($entrenamiento->hora)->format('H:i') : 'Sin hora';
        $ubicacion = $entrenamiento->ubicacion ?? 'Sin ubicación';

        foreach ($jugadores as $equipoUser) {
            $jugador = $equipoUser->jugador;
            if ($jugador) {
                \Filament\Notifications\Notification::make()
                    ->title('Nuevo entrenamiento: ' . $entrenamiento->nombre)
                    ->body("📅 {$fecha} ⏰ {$hora} 📍 {$ubicacion}")
                    ->icon('heroicon-o-calendar')
                    ->color('success')
                    ->viewData([
                        'type' => 'entrenamiento',
                        'entrenamiento_id' => $entrenamiento->id,
                        'equipo_id' => $entrenamiento->equipo_id,
                    ])
                    ->sendToDatabase($jugador);
            }
        }
    }
}
