<?php

namespace App\Filament\Resources\Equipos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;


class EquiposTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Equipo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('categoria')
                    ->label('Categoría')
                    ->sortable(),

                ImageColumn::make('logo_equipo')
                    ->label('Logo')
                    ->disk('public')   // Ajusta según tu storage
                    ->rounded(),

                TextColumn::make('ubi_equipo')
                    ->label('Ubicación')
                    ->sortable(),

                TextColumn::make('contacto_equipo')
                    ->label('Contacto')
                    ->sortable(),


                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable(),
            ])
                    ->filters([

            SelectFilter::make('categoria')
                ->label('Categoría')
                ->options([
                    'Masculino' => 'Masculino',
                    'Femenino' => 'Femenino',
                    'Mixto' => 'Mixto',
                ])
                ->searchable(),

            // 📌 Subcategoría
            SelectFilter::make('categoria_equipo')
                ->label('Subcategoría')
                ->options([
                    'Sub-10' => 'Sub-10',
                    'Sub-12' => 'Sub-12',
                    'Sub-14' => 'Sub-14',
                    'Sub-16' => 'Sub-16',
                    'Sub-18' => 'Sub-18',
                    'Profesional' => 'Profesional',
                ])
                ->searchable(),

            // 📍 Ubicación
            SelectFilter::make('ubi_equipo')
                ->label('Ubicación')
                ->searchable(),

            // 📅 Fecha de creación (rango)
            Filter::make('created_at')
                ->label('Fecha de creación')
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
                            fn ($q) => $q->whereDate('created_at', '>=', $data['desde'])
                        )
                        ->when(
                            $data['hasta'],
                            fn ($q) => $q->whereDate('created_at', '<=', $data['hasta'])
                        );
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

