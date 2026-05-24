<?php

namespace App\Filament\Resources\Partidos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Components\DatePicker;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class PartidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['local', 'visitante', 'instalacion']))
            ->columns([
                Tables\Columns\TextColumn::make('local.nombre')
                    ->label('Equipo Local')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('visitante.nombre')
                    ->label('Equipo Visitante')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('hora')
                    ->label('Hora')
                    ->sortable(),

                Tables\Columns\TextColumn::make('resultado')
                    ->label('Resultado')
                    ->sortable(),

                Tables\Columns\TextColumn::make('fase')
                    ->label('Fase')
                    ->placeholder('—')
                    ->badge()
                    ->color(fn (string|null $state) => match ($state) {
                        'grupo'     => 'gray',
                        'octavos'   => 'info',
                        'cuartos'   => 'warning',
                        'semifinal' => 'primary',
                        'final'     => 'success',
                        'amistoso'  => 'secondary',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string|null $state) => match ($state) {
                        'grupo'     => 'Fase de Grupos',
                        'octavos'   => 'Octavos',
                        'cuartos'   => 'Cuartos',
                        'semifinal' => 'Semifinal',
                        'final'     => 'Final',
                        'amistoso'  => 'Amistoso',
                        default     => $state ?? '—',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('instalacion.nombre')
                    ->label('Instalación')
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('instalacion_id')
                    ->label('Instalación')
                    ->relationship('instalacion', 'nombre')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('fase')
                    ->label('Fase')
                    ->options([
                        'grupo'     => 'Fase de Grupos',
                        'octavos'   => 'Octavos de Final',
                        'cuartos'   => 'Cuartos de Final',
                        'semifinal' => 'Semifinal',
                        'final'     => 'Final',
                        'amistoso'  => 'Amistoso',
                    ]),

                SelectFilter::make('local_id')
                    ->label('Equipo Local')
                    ->relationship('local', 'nombre')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('visitante_id')
                    ->label('Equipo Visitante')
                    ->relationship('visitante', 'nombre')
                    ->searchable()
                    ->preload(),

                Filter::make('fecha')
                    ->label('Fecha del Partido')
                    ->form([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn ($q) => $q->whereDate('fecha', '>=', $data['desde']))
                            ->when($data['hasta'], fn ($q) => $q->whereDate('fecha', '<=', $data['hasta']));
                    }),
                TrashedFilter::make()
                    ->label('Papelera')
                    ->placeholder('Todos los registros')
                    ->trueLabel('Solo eliminados')
                    ->falseLabel('Sin eliminar'),
            ])

            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->label('Exportar'),
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
                    FilamentExportBulkAction::make('export')
                        ->label('Exportar seleccionados'),
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make()
                        ->label('Restaurar seleccionados'),
                    ForceDeleteBulkAction::make()
                        ->label('Borrar permanentemente seleccionados'),
                ]),
            ]);
    }
}
