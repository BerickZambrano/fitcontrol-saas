<?php

namespace App\Filament\Resources\Entrenamientos\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
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
            ->headerActions([
                FilamentExportHeaderAction::make('export')->label('Exportar'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    FilamentExportBulkAction::make('export')->label('Exportar seleccionados'),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}