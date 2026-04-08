<?php

namespace App\Filament\Resources\HistorialMedicos\Pages;

use App\Filament\Resources\HistorialMedicos\HistorialMedicoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHistorialMedicos extends ListRecords
{
    protected static string $resource = HistorialMedicoResource::class;

      public function getTitle(): string
    {
        return 'Historial Médico';
    }

    public function getBreadcrumbs(): array
    {
        return [
            HistorialMedicoResource::getUrl('index') => 'Historial Medico',
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
