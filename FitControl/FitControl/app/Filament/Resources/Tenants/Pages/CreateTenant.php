<?php

namespace App\Filament\Resources\Tenants\Pages;

use App\Filament\Resources\Tenants\TenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected static ?string $title = 'Crear Tenat';


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
