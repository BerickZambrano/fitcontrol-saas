<?php

namespace App\Filament\Resources\Equipos\Pages;

use App\Filament\Resources\Equipos\EquipoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEquipo extends CreateRecord
{
    protected static string $resource = EquipoResource::class;

    protected static ?string $title = 'Crear equipo';

protected function getCreateFormActionLabel(): string
{
    return 'Crear';
}

protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
}

