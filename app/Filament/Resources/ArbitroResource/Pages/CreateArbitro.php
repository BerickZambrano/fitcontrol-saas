<?php

namespace App\Filament\Resources\ArbitroResource\Pages;

use App\Filament\Resources\ArbitroResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArbitro extends CreateRecord
{
    protected static string $resource = ArbitroResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!auth()->user()->hasRole('super_admin')) {
            $data['tenant_id'] = auth()->user()->tenant_id;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->assignRole('Arbitro');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
