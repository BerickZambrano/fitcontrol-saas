<?php

namespace App\Filament\Resources\Entrenamientos\Pages;

use App\Filament\Resources\Entrenamientos\EntrenamientoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEntrenamientos extends ListRecords
{
    protected static string $resource = EntrenamientoResource::class;

      public function getTitle(): string
    {
        return 'Entrenamientos';
    }

    public function getBreadcrumbs(): array
    {
        return [
            EntrenamientoResource::getUrl('index') => 'Entrenamientos',
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
