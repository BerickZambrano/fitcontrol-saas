<?php

namespace App\Filament\Resources\JugadorPerfils\Pages;

use App\Filament\Resources\JugadorPerfils\JugadorPerfilResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJugadorPerfil extends EditRecord
{
    protected static string $resource = JugadorPerfilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
