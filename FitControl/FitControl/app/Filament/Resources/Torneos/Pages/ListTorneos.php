<?php

namespace App\Filament\Resources\Torneos\Pages;

use App\Filament\Resources\Torneos\TorneoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTorneos extends ListRecords
{
    protected static string $resource = TorneoResource::class;

      public function getTitle(): string
    {
        return 'Torneos';
    }

    public function getBreadcrumbs(): array
    {
        return [
            TorneoResource::getUrl('index') => 'Torneo',
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
