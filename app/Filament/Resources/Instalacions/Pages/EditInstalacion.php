<?php

namespace App\Filament\Resources\Instalacions\Pages;

use App\Filament\Resources\Instalacions\InstalacionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInstalacion extends EditRecord
{
    protected static string $resource = InstalacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
