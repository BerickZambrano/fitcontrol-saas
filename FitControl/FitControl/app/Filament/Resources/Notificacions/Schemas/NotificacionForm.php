<?php

namespace App\Filament\Resources\Notificacions\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NotificacionForm
{
      public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('titulo')
                ->label('Título')
                ->required()
                ->maxLength(255),

            Textarea::make('mensaje')
                ->label('Mensaje')
                ->required()
                ->columnSpanFull(),

            Toggle::make('leido')
                ->label('¿Leído?')
                ->default(false),
        ]);
    }
}
