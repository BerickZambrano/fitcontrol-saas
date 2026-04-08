<?php

namespace App\Filament\Resources\HistorialMedicos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class HistorialMedicosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('usuario.name')
                    ->label('Jugador')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tipo_lesion')
                    ->label('Tipo')
                    ->colors([
                        'danger' => 'lesion',
                        'warning' => 'enfermedad',
                        'info' => 'control',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('gravedad')
                    ->label('Gravedad')
                    ->colors([
                        'success' => 'leve',
                        'warning' => 'media',
                        'danger' => 'grave',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fin')
                    ->date()
                    ->sortable(),

                Tables\Columns\IconColumn::make('apto')
                    ->label('Apto')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('usuario_id')
                    ->label('Jugador')
                    ->relationship('usuario', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'lesion' => 'Lesión',
                        'enfermedad' => 'Enfermedad',
                        'control' => 'Control',
                    ]),

                SelectFilter::make('gravedad')
                    ->label('Gravedad')
                    ->options([
                        'leve' => 'Leve',
                        'media' => 'Media',
                        'grave' => 'Grave',
                    ]),

                TernaryFilter::make('apto')
                    ->label('Estado médico')
                    ->trueLabel('Aptos')
                    ->falseLabel('No aptos'),

                Filter::make('fecha_inicio')
                    ->label('Fecha de inicio')
                    ->form([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn ($q) => $q->whereDate('fecha_inicio', '>=', $data['desde']))
                            ->when($data['hasta'], fn ($q) => $q->whereDate('fecha_inicio', '<=', $data['hasta']));
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
