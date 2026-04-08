<?php

namespace App\Filament\Resources\HistorialMedicos\Pages;

use App\Filament\Resources\HistorialMedicos\HistorialMedicoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHistorialMedico extends CreateRecord
{
    protected static string $resource = HistorialMedicoResource::class;

    protected static ?string $title = 'Crear Historial';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
