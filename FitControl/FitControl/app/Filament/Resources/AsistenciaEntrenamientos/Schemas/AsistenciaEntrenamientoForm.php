<?php

namespace App\Filament\Resources\AsistenciaEntrenamientos\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Facades\Filament;

class AsistenciaEntrenamientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                Select::make('entrenamiento_id')
                                    ->label('Entrenamiento')
                                    ->relationship('entrenamiento', 'fecha')
                                    ->required(),

                                Select::make('user_id')
                                    ->label('Jugador')
                                    ->relationship('jugador', 'name')
                                    ->searchable()
                                    ->required(),

                                Toggle::make('presente')
                                    ->label('Presente')
                                    ->default(false),

                                Hidden::make('tenant_id')
                                    ->default(Filament::getTenant()?->id)
                                    ->dehydrated(fn (): bool => true)
                                    ->visible(false),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 2,
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
