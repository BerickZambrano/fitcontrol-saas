<?php

namespace App\Filament\Resources\EquipoUsers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class EquipoUsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withoutGlobalScopes()->with([
                'equipo' => function ($q) {
                    $q->withoutGlobalScopes();
                },
                'jugador' => function ($q) {
                    $q->withoutGlobalScopes();
                },
            ]))
            ->columns([
                Tables\Columns\TextColumn::make('equipo.nombre')
                    ->label('Equipo')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('jugador.name')
                    ->label('Jugador')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date(),

                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fin')
                    ->date(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
             ->filters([

            SelectFilter::make('equipo_id')
                ->label('Equipo')
                ->relationship('equipo', 'nombre')
                ->searchable()
                ->preload(),

            SelectFilter::make('jugador_id')
                ->label('Jugador')
                ->relationship('jugador', 'name')
                ->searchable()
                ->preload(),

            Filter::make('fecha_inicio')
                ->label('Fecha de inicio')
                ->form([
                    DatePicker::make('desde')
                        ->label('Desde'),
                    DatePicker::make('hasta')
                        ->label('Hasta'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when(
                            $data['desde'],
                            fn ($q) => $q->whereDate('fecha_inicio', '>=', $data['desde'])
                        )
                        ->when(
                            $data['hasta'],
                            fn ($q) => $q->whereDate('fecha_inicio', '<=', $data['hasta'])
                        );
                }),

            Filter::make('activos')
                ->label('Solo jugadores activos')
                ->query(fn ($query) => $query->whereNull('fecha_fin')),

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

