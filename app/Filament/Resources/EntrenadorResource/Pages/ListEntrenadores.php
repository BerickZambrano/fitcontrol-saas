<?php

namespace App\Filament\Resources\EntrenadorResource\Pages;

use App\Filament\Resources\EntrenadorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEntrenadores extends ListRecords
{
    protected static string $resource = EntrenadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
