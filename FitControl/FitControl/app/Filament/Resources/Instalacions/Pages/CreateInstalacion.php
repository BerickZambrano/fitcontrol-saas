<?php

namespace App\Filament\Resources\Instalacions\Pages;

use App\Filament\Resources\Instalacions\InstalacionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInstalacion extends CreateRecord
{
    protected static string $resource = InstalacionResource::class;

    protected static ?string $title = 'Crear Instalacion';


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
