<?php

namespace App\Filament\Jugador\Resources\JugadorPerfils\Pages;

use App\Filament\Jugador\Resources\JugadorPerfils\JugadorPerfilResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJugadorPerfil extends CreateRecord
{
    protected static string $resource = JugadorPerfilResource::class;

    protected static ?string $title = 'Crear Perfil de Jugador';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
