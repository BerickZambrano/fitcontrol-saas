<?php

namespace App\Filament\Arbitro\Resources;

use App\Filament\Arbitro\Resources\PartidoResource\Pages;
use App\Models\Partido;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;

class PartidoResource extends Resource
{
    protected static ?string $model = Partido::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-flag';
    protected static ?string $modelLabel = 'Partido Asignado';
    protected static ?string $pluralModelLabel = 'Partidos Asignados';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes()
            ->where('arbitro_id', auth()->id());
    }

    public static function form($schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('resultado')
                    ->label('Resultado')
                    ->disabled(fn ($record) => $record?->estado_partido !== 'finalizado'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('fecha')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hora')
                    ->time(),
                Tables\Columns\TextColumn::make('local.nombre')
                    ->label('Local'),
                Tables\Columns\TextColumn::make('visitante.nombre')
                    ->label('Visitante'),
                Tables\Columns\TextColumn::make('estado_arbitro')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'aceptado' => 'success',
                        'rechazado' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('estado_partido')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'programado' => 'gray',
                        'en_juego' => 'warning',
                        'finalizado' => 'success',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('aceptar')
                    ->label('Aceptar')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation()
                    ->visible(fn (Partido $record): bool => $record->estado_arbitro === 'pendiente')
                    ->action(fn (Partido $record) => $record->update(['estado_arbitro' => 'aceptado'])),

                Action::make('rechazar')
                    ->label('Rechazar')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->requiresConfirmation()
                    ->visible(fn (Partido $record): bool => $record->estado_arbitro === 'pendiente')
                    ->action(function (Partido $record) {
                        $record->update(['estado_arbitro' => 'rechazado']);

                        $arbitro = auth()->user()->name ?? 'Árbitro';
                        $local = $record->local?->nombre ?? 'Equipo Local';
                        $visitante = $record->visitante?->nombre ?? 'Equipo Visitante';
                        $fecha = $record->fecha ? \Carbon\Carbon::parse($record->fecha)->format('d/m/Y') : 'Sin fecha';
                        $hora = $record->hora ? \Carbon\Carbon::parse($record->hora)->format('H:i') : 'Sin hora';

                        // Notify admins and entrenadores of the tenant + super_admins
                        $admins = \App\Models\User::role(['super_admin', 'Administrador', 'Entrenador'])
                            ->where(function ($query) use ($record) {
                                $query->where('tenant_id', $record->tenant_id)
                                      ->orWhereNull('tenant_id');
                            })
                            ->get();

                        foreach ($admins as $admin) {
                            \Filament\Notifications\Notification::make()
                                ->title('Partido Rechazado por el Árbitro')
                                ->body("El árbitro {$arbitro} ha rechazado el partido: {$local} vs {$visitante} (📅 {$fecha} ⏰ {$hora}).")
                                ->danger()
                                ->icon('heroicon-o-x-circle')
                                ->sendToDatabase($admin);
                        }
                    }),

                Action::make('iniciar_partido')
                    ->label('Iniciar Partido')
                    ->color('warning')
                    ->icon('heroicon-o-play')
                    ->requiresConfirmation()
                    ->visible(fn (Partido $record): bool => $record->estado_arbitro === 'aceptado' && $record->estado_partido === 'programado')
                    ->action(fn (Partido $record) => $record->update(['estado_partido' => 'en_juego'])),

                Action::make('finalizar_partido')
                    ->label('Finalizar Partido')
                    ->color('success')
                    ->icon('heroicon-o-stop')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\TextInput::make('resultado')
                            ->label('Resultado (ej. 2-1)')
                            ->required(),
                        Forms\Components\Repeater::make('incidencias')
                            ->label('Novedades / Incidencias del Partido')
                            ->schema([
                                Forms\Components\Select::make('jugador_id')
                                    ->label('Jugador')
                                    ->options(function ($livewire) {
                                        $record = $livewire->getMountedTableActionRecord();
                                        if (! $record) {
                                            return [];
                                        }
                                        $equiposIds = [$record->equipo_local_id, $record->equipo_visitante_id];
                                        $jugadoresIds = \App\Models\EquipoUser::withoutGlobalScopes()
                                            ->whereIn('equipo_id', $equiposIds)
                                            ->pluck('user_id');
                                        return \App\Models\User::whereIn('id', $jugadoresIds)->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->required(),
                                Forms\Components\Select::make('tipo')
                                    ->label('Tipo de Incidencia')
                                    ->options([
                                        'gol' => 'Gol',
                                        'amarilla' => 'Tarjeta Amarilla',
                                        'roja' => 'Tarjeta Roja',
                                        'lesion' => 'Lesión',
                                        'observacion' => 'Observación / Otro',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('minuto')
                                    ->numeric()
                                    ->label('Minuto (aprox)'),
                                Forms\Components\Textarea::make('descripcion')
                                    ->label('Descripción')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->default([]),
                    ])
                    ->visible(fn (Partido $record): bool => $record->estado_arbitro === 'aceptado' && $record->estado_partido === 'en_juego')
                    ->action(function (array $data, Partido $record): void {
                        $record->update([
                            'estado_partido' => 'finalizado',
                            'resultado' => $data['resultado'],
                        ]);

                        // Guardar las incidencias
                        foreach ($data['incidencias'] ?? [] as $incidenciaData) {
                            $record->incidencias()->create([
                                'jugador_id' => $incidenciaData['jugador_id'],
                                'tipo' => $incidenciaData['tipo'],
                                'minuto' => $incidenciaData['minuto'] ?? null,
                                'descripcion' => $incidenciaData['descripcion'] ?? null,
                            ]);
                        }

                        // Agrupar y sincronizar a la tabla de Rendimientos
                        $incidenciasAgrupadas = $record->incidencias()
                            ->get()
                            ->groupBy('jugador_id');

                        foreach ($incidenciasAgrupadas as $jugadorId => $incidenciasJugador) {
                            $goles = $incidenciasJugador->where('tipo', 'gol')->count();
                            $amarillas = $incidenciasJugador->where('tipo', 'amarilla')->count();
                            $rojas = $incidenciasJugador->where('tipo', 'roja')->count();

                            $rendimiento = \App\Models\Rendimiento::firstOrNew([
                                'partido_id' => $record->id,
                                'user_id' => $jugadorId,
                            ]);

                            $rendimiento->tenant_id = \App\Models\User::find($jugadorId)?->tenant_id ?? $record->tenant_id;
                            $rendimiento->goles = $goles;
                            $rendimiento->tarjetas_amarillas = $amarillas;
                            $rendimiento->tarjetas_rojas = $rojas;
                            
                            if (!$rendimiento->exists) {
                                // 90 minutos por defecto para el registro del historial
                                $rendimiento->minutos_jugados = 90;
                            }
                            
                            $rendimiento->save();
                        }
                    }),

                Action::make('ver_novedades')
                    ->label('Ver Novedades')
                    ->color('gray')
                    ->icon('heroicon-o-eye')
                    ->visible(fn (Partido $record): bool => $record->estado_partido === 'finalizado')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->form([
                        Forms\Components\TextInput::make('resultado')
                            ->label('Resultado')
                            ->disabled(),
                        Forms\Components\Repeater::make('incidencias')
                            ->label('Novedades Registradas')
                            ->schema([
                                Forms\Components\TextInput::make('jugador_nombre')
                                    ->label('Jugador')
                                    ->disabled(),
                                Forms\Components\TextInput::make('tipo')
                                    ->label('Tipo de Incidencia')
                                    ->disabled(),
                                Forms\Components\TextInput::make('minuto')
                                    ->label('Minuto')
                                    ->disabled(),
                                Forms\Components\Textarea::make('descripcion')
                                    ->label('Descripción')
                                    ->disabled()
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->disabled()
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),
                    ])
                    ->fillForm(fn (Partido $record): array => [
                        'resultado' => $record->resultado,
                        'incidencias' => $record->incidencias->map(fn ($incidencia) => [
                            'jugador_nombre' => $incidencia->jugador?->name ?? 'N/A',
                            'tipo' => match($incidencia->tipo) {
                                'gol' => 'Gol',
                                'amarilla' => 'Tarjeta Amarilla',
                                'roja' => 'Tarjeta Roja',
                                'lesion' => 'Lesión',
                                'observacion' => 'Observación / Otro',
                                default => $incidencia->tipo,
                            },
                            'minuto' => $incidencia->minuto,
                            'descripcion' => $incidencia->descripcion,
                        ])->toArray(),
                    ]),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PartidoResource\RelationManagers\IncidenciasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartidos::route('/'),
            'edit' => Pages\EditPartido::route('/{record}/edit'),
        ];
    }
}
