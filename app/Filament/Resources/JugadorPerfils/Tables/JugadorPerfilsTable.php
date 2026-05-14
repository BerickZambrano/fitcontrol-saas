<?php

namespace App\Filament\Resources\JugadorPerfils\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class JugadorPerfilsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('user.name')
                    ->label('Jugador')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('posicion')
                    ->label('Posición')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('dorsal')
                    ->label('Dorsal')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('pierna_habil')
                    ->label('Pierna Hábil'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction::make('export')
                    ->label('Exportar'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    \AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction::make('export')
                        ->label('Exportar seleccionados'),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
