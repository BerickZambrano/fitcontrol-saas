<?php

namespace App\Filament\Resources\Rendimientos\Pages;

use App\Filament\Resources\Rendimientos\RendimientoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRendimiento extends CreateRecord
{
    protected static string $resource = RendimientoResource::class;

    protected static ?string $title = 'Crear Rendimiento';


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
