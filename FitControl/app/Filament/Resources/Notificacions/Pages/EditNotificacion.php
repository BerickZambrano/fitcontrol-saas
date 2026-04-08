<?php

namespace App\Filament\Resources\Notificacions\Pages;

use App\Filament\Resources\Notificacions\NotificacionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNotificacion extends EditRecord
{
    protected static string $resource = NotificacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
