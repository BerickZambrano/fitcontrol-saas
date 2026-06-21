<?php

namespace App\Filament\Resources\ArbitroResource\Pages;

use App\Filament\Resources\ArbitroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArbitros extends ListRecords
{
    protected static string $resource = ArbitroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
