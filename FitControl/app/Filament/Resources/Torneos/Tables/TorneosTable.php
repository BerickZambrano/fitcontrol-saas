<?php

namespace App\Filament\Resources\Torneos\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class TorneosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Torneo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('categoria')
                    ->label('Categoría')
                    ->sortable(),

                TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date()
                    ->sortable(),

                TextColumn::make('fecha_fin')
                    ->label('Fin')
                    ->date()
                    ->sortable(),

                BadgeColumn::make('estado')
                    ->colors([
                        'success' => 'activo',
                        'warning' => 'en_progreso',
                        'danger'  => 'finalizado',
                    ])
                    ->label('Estado'),
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'activo' => 'Activo',
                        'en_progreso' => 'En progreso',
                        'finalizado' => 'Finalizado',
                    ]),

                Filter::make('fecha_inicio')
                    ->label('Inicio')
                    ->form([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn($q) => $q->whereDate('fecha_inicio', '>=', $data['desde']))
                            ->when($data['hasta'], fn($q) => $q->whereDate('fecha_inicio', '<=', $data['hasta']));
                    }),

                Filter::make('fecha_fin')
                    ->label('Fin')
                    ->form([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn($q) => $q->whereDate('fecha_fin', '>=', $data['desde']))
                            ->when($data['hasta'], fn($q) => $q->whereDate('fecha_fin', '<=', $data['hasta']));
                    }),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->label('Exportar'),
            ])
            ->recordActions([
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
