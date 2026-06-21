<?php

namespace App\Filament\Resources\EntrenadorResource\Pages;

use App\Filament\Resources\EntrenadorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEntrenador extends CreateRecord
{
    protected static string $resource = EntrenadorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!auth()->user()->hasRole('super_admin')) {
            $data['tenant_id'] = auth()->user()->tenant_id;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->assignRole('Entrenador');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
