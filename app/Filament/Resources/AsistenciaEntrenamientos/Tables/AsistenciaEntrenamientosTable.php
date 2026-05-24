<?php

namespace App\Filament\Resources\AsistenciaEntrenamientos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;

class AsistenciaEntrenamientosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['entrenamiento', 'jugador']))
            ->columns([
                TextColumn::make('entrenamiento.fecha')
                    ->label('Fecha del Entrenamiento')
                    ->date()
                    ->sortable(),

                TextColumn::make('entrenamiento.hora')
                    ->label('Hora')
                    ->sortable(),

                TextColumn::make('entrenamiento.ubicacion')
                    ->label('Ubicación')
                    ->searchable(),

                TextColumn::make('jugador.name')
                    ->label('Jugador')
                    ->searchable()
                    ->limit(30),

                IconColumn::make('presente')
                    ->label('Presente')
                    ->boolean()
                    ->trueIcon('heroicon-m-check-circle')
                    ->falseIcon('heroicon-m-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                // Columna para exportación con texto legible (oculta en pantalla)
                TextColumn::make('presente_texto')
                    ->label('Asistió')
                    ->getStateUsing(fn ($record) => $record->presente ? 'Sí' : 'No')
                    ->hidden(),  // oculta en la tabla pero incluida en el export
            ])
            ->filters([
    SelectFilter::make('presente')
        ->label('Asistencia')
        ->options([
            1 => 'Presente',
            0 => 'Ausente',
        ]),

                // Rango de fechas
                Filter::make('fecha')
                    ->label('Rango de Fechas')
                    ->form([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(fn ($query, $data) =>
                        $query
                            ->when($data['desde'], fn ($q) =>
                                $q->whereHas('entrenamiento',
                                    fn ($q) => $q->whereDate('fecha', '>=', $data['desde'])
                                )
                            )
                            ->when($data['hasta'], fn ($q) =>
                                $q->whereHas('entrenamiento',
                                    fn ($q) => $q->whereDate('fecha', '<=', $data['hasta'])
                                )
                            )
                    ),

                // Jugador
                SelectFilter::make('jugador_id')
                    ->label('Jugador')
                    ->relationship('jugador', 'name')
                    ->searchable(),

                // Solo presentes
                Filter::make('solo_presentes')
                    ->label('Solo Presentes')
                    ->query(fn ($query) => $query->where('presente', true)),
            ])

             ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->label('Exportar'),
            ])

            ->recordActions([
                \Filament\Actions\Action::make('marcarPresente')
                    ->label('Marcar Presente')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->tooltip('Marcar al jugador como presente en el entrenamiento')
                    ->action(function ($record) {
                        $record->update(['presente' => true]);
                    })
                    ->visible(fn ($record) => !$record->presente),
                EditAction::make(),
                DeleteAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    FilamentExportBulkAction::make('export')
                        ->label('Exportar seleccionados'),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

