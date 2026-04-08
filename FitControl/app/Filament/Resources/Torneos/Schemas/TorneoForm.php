<?php

namespace App\Filament\Resources\Torneos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;


class TorneoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
               ->schema([

                    TextInput::make('nombre')
                        ->label('Nombre del torneo')
                        ->required()
                        ->maxLength(255),

                         Select::make('categoria')
                            ->label('Categoría')
                            ->required()
                            ->options([
                                'sub_7' => 'Sub 7',
                                'sub_9' => 'Sub 9',
                                'sub_11' => 'Sub 11',
                                'sub_13' => 'Sub 13',
                                'sub_15' => 'Sub 15',
                                'sub_17' => 'Sub 17',
                                'sub_20' => 'Sub 20',
                                'primera' => 'Primera',
                                'segunda' => 'Segunda',
                                'amateur' => 'Amateur',
                                'femenino' => 'Femenino',
                                'mixto' => 'Mixto',
                                'futsal' => 'Futsal',
                                'futbol_7' => 'Fútbol 7',
                            ]),

                    DatePicker::make('fecha_inicio')
                        ->label('Fecha de inicio')
                        ->required(),

                    DatePicker::make('fecha_fin')
                        ->label('Fecha de fin')
                        ->required()
                        ->after('fecha_inicio'),

                    Select::make('estado')
                        ->label('Estado')
                        ->required()
                        ->options([
                            'activo' => 'Activo',
                            'en_progreso' => 'En progreso',
                            'finalizado' => 'Finalizado',
                        ])
                        ->default('activo'),
                        
                
                ]);
    }
}
