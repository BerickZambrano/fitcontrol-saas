<?php

namespace App\Filament\Arbitro\Resources\PartidoResource\Pages;

use App\Filament\Arbitro\Resources\PartidoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartidos extends ListRecords
{
    protected static string $resource = PartidoResource::class;

    protected string $view = 'filament.arbitro.pages.list-partidos';

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
