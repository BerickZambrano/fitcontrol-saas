<?php

namespace App\Filament\Resources\Pagos\Pages;

use App\Filament\Resources\Pagos\PagoResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePago extends CreateRecord
{
    protected static string $resource = PagoResource::class;

    protected static ?string $title = 'Crear Pago';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
