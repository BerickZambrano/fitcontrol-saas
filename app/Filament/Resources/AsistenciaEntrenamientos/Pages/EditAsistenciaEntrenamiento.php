<?php

namespace App\Filament\Resources\AsistenciaEntrenamientos\Pages;

use App\Filament\Resources\AsistenciaEntrenamientos\AsistenciaEntrenamientoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAsistenciaEntrenamiento extends EditRecord
{
    protected static string $resource = AsistenciaEntrenamientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
