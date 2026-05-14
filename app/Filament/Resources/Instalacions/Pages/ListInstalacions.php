<?php

namespace App\Filament\Resources\Instalacions\Pages;

use App\Filament\Resources\Instalacions\InstalacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInstalacions extends ListRecords
{
    protected static string $resource = InstalacionResource::class;

      public function getTitle(): string
    {
        return 'Instalaciones';
    }

    public function getBreadcrumbs(): array
    {
        return [
            InstalacionResource::getUrl('index') => 'Instalaciones',
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
