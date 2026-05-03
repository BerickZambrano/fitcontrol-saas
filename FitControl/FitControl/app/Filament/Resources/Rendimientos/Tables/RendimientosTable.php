<?php

namespace App\Filament\Resources\Rendimientos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class RendimientosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('user'))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Jugador')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('minutos_jugados')
                    ->label('Minutos')
                    ->sortable(),

                Tables\Columns\TextColumn::make('goles')
                    ->label('Goles')
                    ->sortable(),

                Tables\Columns\TextColumn::make('asistencias')
                    ->label('Asistencias')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tarjetas_amarillas')
                    ->label('🟨')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tarjetas_rojas')
                    ->label('🟥')
                    ->sortable(),

                Tables\Columns\TextColumn::make('evaluacion')
                    ->label('Evaluación')
                    ->numeric(decimalPlaces: 1)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('user_id')
                    ->label('Jugador')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('fecha')
                    ->label('Fecha de registro')
                    ->form([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn ($q) => $q->whereDate('created_at', '>=', $data['desde']))
                            ->when($data['hasta'], fn ($q) => $q->whereDate('created_at', '<=', $data['hasta']));
                    }),

                Filter::make('evaluacion')
                    ->label('Evaluación')
                    ->form([
                        TextInput::make('min')->numeric(),
                        TextInput::make('max')->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['min'], fn ($q) => $q->where('evaluacion', '>=', $data['min']))
                            ->when($data['max'], fn ($q) => $q->where('evaluacion', '<=', $data['max']));
                    }),
            ])

            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->label('Exportar')
                    ->defaultFormat('pdf'),
            ])

            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    FilamentExportBulkAction::make('export')
                        ->label('Exportar seleccionados')
                        ->defaultFormat('xlsx'),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
