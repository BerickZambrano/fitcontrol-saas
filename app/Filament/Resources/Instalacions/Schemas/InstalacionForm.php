<?php

namespace App\Filament\Resources\Instalacions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InstalacionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
                    ->schema([

                        TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(100),

                        Select::make('tipo')
                            ->label('Tipo')
                            ->options([
                                'cancha' => 'Cancha',
                                'gimnasio' => 'Gimnasio',
                                'piscina' => 'Piscina',
                                'estadio' => 'Estadio',
                            ])
                            ->required(),

                        TextInput::make('ubicacion')
                            ->label('Ubicación')
                            ->maxLength(255)
                            ->required(),

                        TextInput::make('capacidad')
                            ->label('Capacidad')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(999999)
                            ->validationMessages(['max' => 'La capacidad ingresada es demasiado grande.']),

                        Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'disponible' => 'Disponible',
                                'ocupada' => 'Ocupada',
                                'mantenimiento' => 'Mantenimiento',
                            ])
                            ->required(),
                    ]);
                       }
}