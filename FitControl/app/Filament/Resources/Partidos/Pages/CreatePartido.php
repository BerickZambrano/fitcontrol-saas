<?php

namespace App\Filament\Resources\Partidos\Pages;

use App\Filament\Resources\Partidos\PartidoResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePartido extends CreateRecord
{
    protected static string $resource = PartidoResource::class;

    protected static ?string $title = 'Crear Partido';


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
