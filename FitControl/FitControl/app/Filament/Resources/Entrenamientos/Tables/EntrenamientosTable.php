<?php

namespace App\Filament\Resources\Entrenamientos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class EntrenamientosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('equipo'))
            ->columns([
                TextColumn::make('nombre')->label('Nombre')->searchable()->sortable(),
                TextColumn::make('fecha')->label('Fecha')->date()->sortable(),
                TextColumn::make('hora')->label('Hora')->sortable(),
                TextColumn::make('ubicacion')->label('Ubicación')->searchable(),
                TextColumn::make('equipo.nombre')->label('Equipo')->searchable()->sortable(),
            ])
            ->defaultSort('fecha', 'desc')
            ->filters([
                TrashedFilter::make()
                    ->label('Papelera')
                    ->placeholder('Todos los registros')
                    ->trueLabel('Solo eliminados')
                    ->falseLabel('Sin eliminar'),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')->label('Exportar'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make()
                    ->label('Restaurar'),
                ForceDeleteAction::make()
                    ->label('Borrar permanentemente'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    FilamentExportBulkAction::make('export')->label('Exportar seleccionados'),
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make()
                        ->label('Restaurar seleccionados'),
                    ForceDeleteBulkAction::make()
                        ->label('Borrar permanentemente seleccionados'),
                ]),
            ]);
    }
}