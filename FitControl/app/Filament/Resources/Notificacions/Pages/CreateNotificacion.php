<?php

namespace App\Filament\Resources\Notificacions\Pages;

use App\Filament\Resources\Notificacions\NotificacionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNotificacion extends CreateRecord
{
    protected static string $resource = NotificacionResource::class;

    protected static ?string $title = 'Crear Notificación';
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
