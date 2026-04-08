<?php

namespace App\Filament\Resources\Equipos\Pages;

use App\Filament\Resources\Equipos\EquipoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEquipos extends ListRecords
{
    protected static string $resource = EquipoResource::class;

      public function getTitle(): string
    {
        return 'Equipos';
    }

    public function getBreadcrumbs(): array
    {
        return [
            EquipoResource::getUrl('index') => 'Equipo',
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
