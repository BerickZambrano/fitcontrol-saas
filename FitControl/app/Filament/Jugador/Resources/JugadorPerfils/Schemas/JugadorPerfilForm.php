<?php

namespace App\Filament\Jugador\Resources\JugadorPerfils\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section as ComponentsSection;

class JugadorPerfilForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                ComponentsSection::make('Jugador')
                    ->columns(2)
                    ->schema([

                        Select::make('user_id')
                            ->label('Jugador')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),

                        TextInput::make('dorsal')
                            ->label('Dorsal')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(99)
                            ->required(),
                    ]),

                ComponentsSection::make('Datos del perfil')
                    ->columns(3)
                    ->schema([

                        Select::make('posicion')
                            ->label('Posición')
                            ->options([
                                'portero' => 'Portero',
                                'defensa' => 'Defensa',
                                'mediocampo' => 'Mediocampo',
                                'delantero' => 'Delantero',
                            ])
                            ->required(),

                        TextInput::make('altura')
                            ->label('Altura (cm)')
                            ->numeric()
                            ->minValue(100)
                            ->maxValue(230),

                        TextInput::make('peso')
                            ->label('Peso (kg)')
                            ->numeric()
                            ->minValue(30)
                            ->maxValue(150),

                        Select::make('pierna_habil')
                            ->label('Pierna hábil')
                            ->options([
                                'derecha' => 'Derecha',
                                'izquierda' => 'Izquierda',
                                'ambas' => 'Ambas',
                            ])
                            ->required(),
                    ]),
            ]);
    }
}
