<?php

namespace App\Filament\Resources\Entrenamientos\Pages;

use App\Filament\Resources\Entrenamientos\EntrenamientoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEntrenamiento extends EditRecord
{
    protected static string $resource = EntrenamientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
