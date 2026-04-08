<?php

namespace App\Filament\Resources\AsistenciaEntrenamientos\Pages;

use App\Filament\Resources\AsistenciaEntrenamientos\AsistenciaEntrenamientoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAsistenciaEntrenamiento extends CreateRecord
{
    protected static string $resource = AsistenciaEntrenamientoResource::class;

    protected static ?string $title = 'Crear Entrenamiento';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
