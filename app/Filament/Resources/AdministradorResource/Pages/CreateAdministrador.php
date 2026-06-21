<?php

namespace App\Filament\Resources\AdministradorResource\Pages;

use App\Filament\Resources\AdministradorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdministrador extends CreateRecord
{
    protected static string $resource = AdministradorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!auth()->user()->hasRole('super_admin')) {
            $data['tenant_id'] = auth()->user()->tenant_id;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->assignRole('Administrador');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
