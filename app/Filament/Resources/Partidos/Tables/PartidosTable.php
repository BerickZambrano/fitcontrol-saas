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
            ->recordClasses(fn (\App\Models\Partido $record): ?string => $record->estado_arbitro === 'rechazado' ? 'bg-red-50/50 dark:bg-red-950/10 border-l-4 border-red-500' : null)
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

                Tables\Columns\TextColumn::make('arbitro.name')
                    ->label('Árbitro')
                    ->placeholder('Sin asignar')
                    ->description(fn (\App\Models\Partido $record): ?string => $record->arbitro_id ? match($record->estado_arbitro) {
                        'pendiente' => '⏳ Pendiente',
                        'aceptado' => '✅ Aceptado',
                        'rechazado' => '❌ Rechazado',
                        default => null,
                    } : null)
                    ->color(fn (\App\Models\Partido $record): ?string => $record->arbitro_id ? match($record->estado_arbitro) {
                        'pendiente' => 'warning',
                        'aceptado' => 'success',
                        'rechazado' => 'danger',
                        default => null,
                    } : null)
                    ->sortable()
                    ->searchable(),

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
                \Filament\Actions\ImportAction::make('import')
                    ->label('Importar CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->importer(\App\Filament\Imports\PartidoImporter::class),
                FilamentExportHeaderAction::make('export')
                    ->label('Exportar')
                    ->fileName('Reporte_Partidos_' . now()->format('Ymd_His'))
                    ->defaultPageOrientation('landscape')
                    ->withColumns([
                        Tables\Columns\TextColumn::make('local.nombre')->label('Equipo Local'),
                        Tables\Columns\TextColumn::make('visitante.nombre')->label('Equipo Visitante'),
                        Tables\Columns\TextColumn::make('fecha')->label('Fecha')->date('d/m/Y'),
                        Tables\Columns\TextColumn::make('hora')->label('Hora'),
                        Tables\Columns\TextColumn::make('resultado')->label('Resultado'),
                        Tables\Columns\TextColumn::make('fase')
                            ->label('Fase')
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'grupo'     => 'Fase de Grupos',
                                'octavos'   => 'Octavos',
                                'cuartos'   => 'Cuartos',
                                'semifinal' => 'Semifinal',
                                'final'     => 'Final',
                                'amistoso'  => 'Amistoso',
                                default     => $state ?? '—',
                            }),
                        Tables\Columns\TextColumn::make('instalacion.nombre')->label('Instalación'),
                        Tables\Columns\TextColumn::make('arbitro')
                            ->label('Árbitro y Estado')
                            ->getStateUsing(function (\App\Models\Partido $record) {
                                if (!$record->arbitro_id) return 'Sin asignar';
                                $estado = match($record->estado_arbitro) {
                                    'pendiente' => 'Pendiente',
                                    'aceptado' => 'Aceptado',
                                    'rechazado' => 'Rechazado',
                                    default => 'Desconocido',
                                };
                                return ($record->arbitro->name ?? 'Desconocido') . ' (' . $estado . ')';
                            }),
                    ]),
            ])

            ->recordActions([
                \Filament\Actions\Action::make('reasignar_arbitro')
                    ->label('Reasignar Árbitro')
                    ->button()
                    ->icon('heroicon-o-user-circle')
                    ->color('warning')
                    ->form([
                        \Filament\Forms\Components\Select::make('arbitro_id')
                            ->label('Seleccionar Árbitro')
                            ->options(function () {
                                return \App\Models\User::role('Arbitro')
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->action(function (array $data, \App\Models\Partido $record): void {
                        $record->update([
                            'arbitro_id' => $data['arbitro_id'],
                            'estado_arbitro' => 'pendiente',
                        ]);

                        // Notify new referee
                        $arbitro = \App\Models\User::find($data['arbitro_id']);
                        if ($arbitro) {
                            $local = $record->local?->nombre ?? 'Equipo Local';
                            $visitante = $record->visitante?->nombre ?? 'Equipo Visitante';
                            $fecha = $record->fecha ? \Carbon\Carbon::parse($record->fecha)->format('d/m/Y') : 'Sin fecha';
                            $hora = $record->hora ? \Carbon\Carbon::parse($record->hora)->format('H:i') : 'Sin hora';

                            \Filament\Notifications\Notification::make()
                                ->title('Asignación de Partido')
                                ->body("Se te ha asignado un partido: {$local} vs {$visitante} (📅 {$fecha} ⏰ {$hora}). Por favor acéptalo o recházalo.")
                                ->warning()
                                ->icon('heroicon-o-flag')
                                ->sendToDatabase($arbitro);
                        }
                    })
                    ->visible(fn (\App\Models\Partido $record): bool => 
                        $record->estado_arbitro === 'rechazado'
                    ),
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
                        ->label('Exportar seleccionados')
                        ->fileName('Reporte_Partidos_Seleccionados_' . now()->format('Ymd_His'))
                        ->defaultPageOrientation('landscape')
                        ->withColumns([
                            Tables\Columns\TextColumn::make('local.nombre')->label('Equipo Local'),
                            Tables\Columns\TextColumn::make('visitante.nombre')->label('Equipo Visitante'),
                            Tables\Columns\TextColumn::make('fecha')->label('Fecha')->date('d/m/Y'),
                            Tables\Columns\TextColumn::make('hora')->label('Hora'),
                            Tables\Columns\TextColumn::make('resultado')->label('Resultado'),
                            Tables\Columns\TextColumn::make('fase')
                                ->label('Fase')
                                ->formatStateUsing(fn ($state) => match ($state) {
                                    'grupo'     => 'Fase de Grupos',
                                    'octavos'   => 'Octavos',
                                    'cuartos'   => 'Cuartos',
                                    'semifinal' => 'Semifinal',
                                    'final'     => 'Final',
                                    'amistoso'  => 'Amistoso',
                                    default     => $state ?? '—',
                                }),
                            Tables\Columns\TextColumn::make('instalacion.nombre')->label('Instalación'),
                            Tables\Columns\TextColumn::make('arbitro')
                                ->label('Árbitro y Estado')
                                ->getStateUsing(function (\App\Models\Partido $record) {
                                    if (!$record->arbitro_id) return 'Sin asignar';
                                    $estado = match($record->estado_arbitro) {
                                        'pendiente' => 'Pendiente',
                                        'aceptado' => 'Aceptado',
                                        'rechazado' => 'Rechazado',
                                        default => 'Desconocido',
                                    };
                                    return ($record->arbitro->name ?? 'Desconocido') . ' (' . $estado . ')';
                                }),
                        ]),
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make()
                        ->label('Restaurar seleccionados'),
                    ForceDeleteBulkAction::make()
                        ->label('Borrar permanentemente seleccionados'),
                ]),
            ]);
    }
}
