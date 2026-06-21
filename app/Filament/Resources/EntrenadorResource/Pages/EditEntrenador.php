<?php

namespace App\Filament\Resources\EntrenadorResource\Pages;

use App\Filament\Resources\EntrenadorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEntrenador extends EditRecord
{
    protected static string $resource = EntrenadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
