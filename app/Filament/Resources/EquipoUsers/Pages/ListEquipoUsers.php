<?php

namespace App\Filament\Resources\EquipoUsers\Pages;

use App\Filament\Resources\EquipoUsers\EquipoUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEquipoUsers extends ListRecords
{
    protected static string $resource = EquipoUserResource::class;

      public function getTitle(): string
    {
        return 'Asignar Equipo';
    }

    public function getBreadcrumbs(): array
    {
        return [
            EquipoUserResource::getUrl('index') => 'Equipo',
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
