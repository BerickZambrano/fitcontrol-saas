<?php

namespace App\Filament\Resources\ArbitroResource\Pages;

use App\Filament\Resources\ArbitroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArbitro extends EditRecord
{
    protected static string $resource = ArbitroResource::class;

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
