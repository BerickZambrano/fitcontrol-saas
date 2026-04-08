<?php

namespace App\Filament\Resources\HistorialMedicos\Pages;

use App\Filament\Resources\HistorialMedicos\HistorialMedicoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHistorialMedico extends EditRecord
{
    protected static string $resource = HistorialMedicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
