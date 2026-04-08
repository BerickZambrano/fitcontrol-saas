<?php

namespace App\Filament\Resources\Entrenamientos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use App\Models\Equipo;

class EntrenamientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre del Entrenamiento')
                    ->required()
                    ->maxLength(100),
            
                DatePicker::make('fecha')
                    ->label('Fecha')
                    ->required(),

                TimePicker::make('hora')
                    ->label('Hora')
                    ->required(),

                TextInput::make('ubicacion')
                    ->label('Ubicación')
                    ->required()
                    ->maxLength(100),

                Select::make('equipo_id')
                    ->label('Equipo')
                    ->relationship('equipo', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),

                Hidden::make('tenant_id'),
            ]);
    }
}
