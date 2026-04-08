<?php

namespace App\Filament\Resources\Rendimientos\Pages;

use App\Filament\Resources\Rendimientos\RendimientoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRendimiento extends EditRecord
{
    protected static string $resource = RendimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
