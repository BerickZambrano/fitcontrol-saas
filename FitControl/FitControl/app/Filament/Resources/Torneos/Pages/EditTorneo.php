<?php

namespace App\Filament\Resources\Torneos\Pages;

use App\Filament\Resources\Torneos\TorneoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTorneo extends EditRecord
{
    protected static string $resource = TorneoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
