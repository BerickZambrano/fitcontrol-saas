<?php

namespace App\Filament\Arbitro\Resources\PartidoResource\Pages;

use App\Filament\Arbitro\Resources\PartidoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartido extends EditRecord
{
    protected static string $resource = PartidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
