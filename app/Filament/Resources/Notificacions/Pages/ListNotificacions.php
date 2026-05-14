<?php

namespace App\Filament\Resources\Notificacions\Pages;

use App\Filament\Resources\Notificacions\NotificacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNotificacions extends ListRecords
{
    protected static string $resource = NotificacionResource::class;

      public function getTitle(): string
    {
        return 'Notificaciones';
    }

    public function getBreadcrumbs(): array
    {
        return [
            NotificacionResource::getUrl('index') => 'Notificaciones',
            'Listado',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
