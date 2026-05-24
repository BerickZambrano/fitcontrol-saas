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
            Select::make('tipo_destinatario')
                ->label('Tipo de Destinatario')
                ->options([
                    'individual' => 'Jugador Individual',
                    'equipo' => 'Equipo Completo',
                    'todos' => 'Todos los Jugadores',
                ])
                ->default('individual')
                ->live()
                ->required(),

            Select::make('user_id')
                ->label('Destinatario')
                ->options(function () {
                    $query = User::role('Jugador');
                    if (!auth()->user()->hasRole('super_admin')) {
                        $query->where('tenant_id', auth()->user()->tenant_id);
                    }
                    return $query->pluck('name', 'id');
                })
                ->searchable()
                ->preload()
                ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get): bool => $get('tipo_destinatario') === 'individual')
                ->required(fn (\Filament\Schemas\Components\Utilities\Get $get): bool => $get('tipo_destinatario') === 'individual'),

            Select::make('equipo_id')
                ->label('Equipo')
                ->options(function () {
                    $query = \App\Models\Equipo::query();
                    if (!auth()->user()->hasRole('super_admin')) {
                        $query->where('tenant_id', auth()->user()->tenant_id);
                    }
                    return $query->pluck('nombre', 'id');
                })
                ->searchable()
                ->preload()
                ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get): bool => $get('tipo_destinatario') === 'equipo')
                ->required(fn (\Filament\Schemas\Components\Utilities\Get $get): bool => $get('tipo_destinatario') === 'equipo'),

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
