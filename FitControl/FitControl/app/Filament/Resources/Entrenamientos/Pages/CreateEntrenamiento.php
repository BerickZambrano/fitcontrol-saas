<?php

namespace App\Filament\Resources\Entrenamientos\Pages;

use App\Filament\Resources\Entrenamientos\EntrenamientoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEntrenamiento extends CreateRecord
{
    protected static string $resource = EntrenamientoResource::class;

        protected static ?string $title = 'Crear Entrenamiento';


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
