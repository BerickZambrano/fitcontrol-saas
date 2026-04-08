<?php

namespace App\Filament\Resources\HistorialMedicos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section as ComponentsSection;

class HistorialMedicoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                ComponentsSection::make('Información del jugador')
                    ->columns(2)
                    ->schema([

                        Select::make('user_id')
                            ->label('Jugador')
                            ->relationship('usuario', 'name')
                            ->searchable()
                            ->required(),

                        Select::make('tipo_lesion')
                            ->label('Tipo')
                            ->options([
                                'lesion' => 'Lesión',
                                'enfermedad' => 'Enfermedad',
                                'control' => 'Control',
                            ])
                            ->required(),
                    ]),

                ComponentsSection::make('Detalle médico')
                    ->schema([

                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(4)
                            ->required(),

                        Select::make('gravedad')
                            ->label('Gravedad')
                            ->options([
                                'leve' => 'Leve',
                                'media' => 'Media',
                                'grave' => 'Grave',
                            ])
                            ->required(),

                        Toggle::make('apto')
                            ->label('Apto para jugar')
                            ->default(false),
                    ]),

                ComponentsSection::make('Fechas')
                    ->columns(2)
                    ->schema([

                        DatePicker::make('fecha_inicio')
                            ->label('Fecha inicio')
                            ->required(),

                        DatePicker::make('fecha_fin')
                            ->label('Fecha fin')
                            ->afterOrEqual('fecha_inicio'),
                    ]),
            ]);
    }
}
