<?php

namespace App\Filament\Jugador\Resources\JugadorPerfils\Pages;

use App\Filament\Jugador\Resources\JugadorPerfils\JugadorPerfilResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJugadorPerfils extends ListRecords
{
    protected static string $resource = JugadorPerfilResource::class;

      public function getTitle(): string
    {
        return 'Perfil Jugador';
    }

    public function getBreadcrumbs(): array
    {
        return [
            JugadorPerfilResource::getUrl('index') => 'Perfil de Jugador',
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
