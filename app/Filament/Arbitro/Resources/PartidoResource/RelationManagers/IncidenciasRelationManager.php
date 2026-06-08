<?php

namespace App\Filament\Arbitro\Resources\PartidoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\User;

class IncidenciasRelationManager extends RelationManager
{
    protected static string $relationship = 'incidencias';
    protected static ?string $recordTitleAttribute = 'tipo';

    public function form($schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('jugador_id')
                    ->label('Jugador')
                    ->options(function ($livewire) {
                        $partido = $livewire->getOwnerRecord();
                        $equiposIds = [$partido->equipo_local_id, $partido->equipo_visitante_id];
                        
                        $jugadoresIds = \App\Models\EquipoUser::withoutGlobalScopes()
                            ->whereIn('equipo_id', $equiposIds)
                            ->pluck('user_id');
                            
                        return \App\Models\User::whereIn('id', $jugadoresIds)->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),
                 Forms\Components\Select::make('tipo')
                    ->label('Tipo de Incidencia')
                    ->options([
                        'gol' => 'Gol',
                        'amarilla' => 'Tarjeta Amarilla',
                        'roja' => 'Tarjeta Roja',
                        'lesion' => 'Lesión',
                        'observacion' => 'Observación / Otro',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('minuto')
                    ->numeric()
                    ->label('Minuto (aprox)'),
                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tipo')
            ->columns([
                Tables\Columns\TextColumn::make('jugador.name')->label('Jugador'),
                Tables\Columns\TextColumn::make('tipo')->badge()->color(fn ($state) => match($state) {
                    'gol' => 'success',
                    'amarilla' => 'warning',
                    'roja' => 'danger',
                    'lesion' => 'info',
                    'observacion' => 'gray',
                }),
                Tables\Columns\TextColumn::make('minuto')->label('Minuto'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->visible(fn ($livewire) => $livewire->getOwnerRecord()->estado_partido !== 'finalizado'),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Sin acciones masivas
            ]);
    }
}
