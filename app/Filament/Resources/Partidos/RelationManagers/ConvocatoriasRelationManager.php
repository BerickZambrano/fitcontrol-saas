<?php

namespace App\Filament\Resources\Partidos\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ConvocatoriasRelationManager extends RelationManager
{
    protected static string $relationship = 'convocatorias';
    protected static ?string $recordTitleAttribute = 'estado_asistencia';
    protected static ?string $title = 'Jugadores Convocados';

    public function form($schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('jugador_id')
                    ->label('Jugador')
                    ->options(function ($livewire) {
                        $partido = $livewire->getOwnerRecord();
                        // Filtrar los que tienen sancion
                        $jugadoresConSancion = \App\Models\Sancion::where('estado', 'activa')->pluck('jugador_id');
                        $entrenador = auth()->user();
                        
                        return User::role('Jugador')
                            ->where('tenant_id', $entrenador->tenant_id)
                            ->whereNotIn('id', $jugadoresConSancion)
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required()
                    ->unique(
                        table: 'convocatorias',
                        column: 'jugador_id',
                        modifyRuleUsing: fn (\Illuminate\Validation\Rules\Unique $rule, $livewire) => $rule->where('partido_id', $livewire->getOwnerRecord()->id),
                        ignoreRecord: true
                    )
                    ->validationMessages([
                        'unique' => 'Este jugador ya está convocado para este partido.',
                    ]),
                Forms\Components\Hidden::make('equipo_id')
                    ->default(fn ($livewire) => $livewire->getOwnerRecord()->equipo_local_id),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('estado_asistencia')
            ->columns([
                Tables\Columns\TextColumn::make('jugador.name')->label('Jugador'),
                Tables\Columns\TextColumn::make('equipo.nombre')->label('Equipo'),
                Tables\Columns\TextColumn::make('estado_asistencia')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'convocado' => 'warning',
                        'confirmado' => 'success',
                        'rechazado' => 'danger',
                        'asistio' => 'info',
                        'falto' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Convocar Jugador'),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
