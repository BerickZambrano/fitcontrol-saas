<?php

namespace App\Filament\Resources\Notificacions\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NotificacionForm
{
      public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->label('Destinatario')
                ->options(function () {
                    $query = User::query();
                    if (!auth()->user()->hasRole('super_admin')) {
                        $query->where('tenant_id', auth()->user()->tenant_id);
                    }
                    return $query->pluck('name', 'id');
                })
                ->searchable()
                ->preload()
                ->required(),

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
