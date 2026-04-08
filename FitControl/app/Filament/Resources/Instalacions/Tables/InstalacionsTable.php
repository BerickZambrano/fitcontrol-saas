<?php

namespace App\Filament\Resources\Instalacions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class InstalacionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->colors([
                        'success' => 'cancha',
                        'primary' => 'gimnasio',
                        'info' => 'piscina',
                        'warning' => 'estadio',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('ubicacion')
                    ->label('Ubicación')
                    ->searchable(),

                Tables\Columns\TextColumn::make('capacidad')
                    ->label('Capacidad')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'success' => 'disponible',
                        'warning' => 'ocupada',
                        'danger' => 'mantenimiento',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'cancha' => 'Cancha',
                        'gimnasio' => 'Gimnasio',
                        'piscina' => 'Piscina',
                        'estadio' => 'Estadio',
                    ]),

                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'disponible' => 'Disponible',
                        'ocupada' => 'Ocupada',
                        'mantenimiento' => 'Mantenimiento',
                    ]),

                Filter::make('created_at')
                    ->label('Fecha de creación')
                    ->form([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn ($q) => $q->whereDate('created_at', '>=', $data['desde']))
                            ->when($data['hasta'], fn ($q) => $q->whereDate('created_at', '<=', $data['hasta']));
                    }),
            ])

            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->label('Exportar')
                    ->defaultFormat('pdf')
                    ->directDownload(),
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
