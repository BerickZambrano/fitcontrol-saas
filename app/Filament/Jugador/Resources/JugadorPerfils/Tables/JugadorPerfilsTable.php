<?php

namespace App\Filament\Jugador\Resources\JugadorPerfils\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class JugadorPerfilsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Jugador')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('posicion')
                    ->label('Posición')
                    ->colors([
                        'primary' => 'portero',
                        'success' => 'defensa',
                        'warning' => 'mediocampo',
                        'danger' => 'delantero',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('dorsal')
                    ->label('Dorsal')
                    ->sortable(),

                Tables\Columns\TextColumn::make('altura')
                    ->label('Altura (cm)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('peso')
                    ->label('Peso (kg)')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('pierna_habil')
                    ->label('Pierna hábil')
                    ->colors([
                        'success' => 'derecha',
                        'warning' => 'izquierda',
                        'primary' => 'ambas',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('user_id')
                    ->label('Jugador')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('posicion')
                    ->label('Posición')
                    ->options([
                        'portero' => 'Portero',
                        'defensa' => 'Defensa',
                        'mediocampo' => 'Mediocampo',
                        'delantero' => 'Delantero',
                    ]),

                SelectFilter::make('pierna_habil')
                    ->label('Pierna hábil')
                    ->options([
                        'derecha' => 'Derecha',
                        'izquierda' => 'Izquierda',
                        'ambas' => 'Ambas',
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
