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
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                      ->orWhere('fecha_fin', '>=', now()->toDateString());
            })
            ->get();

        foreach ($jugadores as $equipoUser) {
            $jugador = $equipoUser->jugador;
            if ($jugador) {
                $jugador->notify(new NuevoEntrenamientoNotification($entrenamiento));
            }
        }
    }
}
