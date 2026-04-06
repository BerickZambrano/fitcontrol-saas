<?php

namespace App\Filament\Resources\Partidos\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class PartidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\DatePicker::make('fecha')
                ->label('Fecha')
                ->required(),

            Forms\Components\TimePicker::make('hora')
                ->label('Hora')
                ->required(),
Forms\Components\Select::make('equipo_local_id')
    ->label('Equipo Local')
    ->required()
    ->options(fn () => \App\Models\Equipo::pluck('nombre', 'id'))
    ->searchable(),

Forms\Components\Select::make('equipo_visitante_id')
    ->label('Equipo Visitante')
    ->required()
    ->options(fn () => \App\Models\Equipo::pluck('nombre', 'id'))
    ->searchable(),

Forms\Components\TextInput::make('resultado')
    ->label('Resultado')
    ->maxLength(255),

 Forms\Components\Select::make('torneo_id')
     ->label('Torneo')
     ->required()
     ->options(fn () => \App\Models\Torneo::pluck('nombre', 'id'))
     ->searchable(),
     
Forms\Components\Hidden::make('tenant_id')
    ->default(fn () => auth()->user()->tenant_id)
    ->required(),

        ]);
    }
}
