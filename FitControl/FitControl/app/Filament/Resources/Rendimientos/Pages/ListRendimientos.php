<?php

namespace App\Filament\Resources\Rendimientos\Pages;

use App\Filament\Resources\Rendimientos\RendimientoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRendimientos extends ListRecords
{
    protected static string $resource = RendimientoResource::class;

      public function getTitle(): string
    {
        return 'Rendimiento';
    }

    public function getBreadcrumbs(): array
    {
        return [
            RendimientoResource::getUrl('index') => 'Rendimientos',
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
