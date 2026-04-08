<?php

namespace App\Filament\Resources\AsistenciaEntrenamientos\Pages;

use App\Filament\Resources\AsistenciaEntrenamientos\AsistenciaEntrenamientoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAsistenciaEntrenamientos extends ListRecords
{
    protected static string $resource = AsistenciaEntrenamientoResource::class;

    public function getTitle(): string
    {
        return 'Asistencia Entrenamientos';
    }

    public function getBreadcrumbs(): array
    {
        return [
            AsistenciaEntrenamientoResource::getUrl('index') => 'Asistencia Entrenamientos',
            'Listado',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}