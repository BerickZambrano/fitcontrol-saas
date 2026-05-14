<?php

namespace App\Filament\Resources\JugadorPerfils\Pages;

use App\Filament\Resources\JugadorPerfils\JugadorPerfilResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJugadorPerfils extends ListRecords
{
    protected static string $resource = JugadorPerfilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
