<?php

namespace App\Filament\Resources\Torneos\Pages;

use App\Filament\Resources\Torneos\TorneoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTorneo extends CreateRecord
{
    protected static string $resource = TorneoResource::class;

    protected static ?string $title = 'Crear Torneo';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
