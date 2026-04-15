<?php

namespace App\Filament\Resources\Entrenamientos\Pages;

use App\Filament\Resources\Entrenamientos\EntrenamientoResource;
use App\Models\EquipoUser;
use App\Notifications\NuevoEntrenamientoNotification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Notification;

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

        // Obtener todos los jugadores activos del equipo
        $jugadores = EquipoUser::where('equipo_id', $entrenamiento->equipo_id)
            ->whereNull('fecha_fin')
            ->get();

        // Enviar notificación a cada jugador
        foreach ($jugadores as $equipoUser) {
            $jugador = $equipoUser->jugador;
            if ($jugador) {
                $jugador->notify(new NuevoEntrenamientoNotification($entrenamiento));
            }
        }
    }
}
