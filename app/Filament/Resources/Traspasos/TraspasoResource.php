<?php

namespace App\Filament\Resources\Traspasos;

use App\Filament\Resources\Traspasos\Pages\ListTraspasos;
use App\Models\Traspaso;
use App\Models\User;
use App\Models\Equipo;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Icons\Heroicon;

class TraspasoResource extends Resource
{
    protected static ?string $model = Traspaso::class;

    protected static ?string $navigationLabel = 'Traspasos';
    protected static ?string $modelLabel = 'Traspaso';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('Entrenador')) {
            // El entrenador solo ve los traspasos que involucran a su equipo (origen o destino)
            $equipoId = auth()->user()->equipoUser?->equipo_id;
            
            if ($equipoId) {
                $query->where(function ($q) use ($equipoId) {
                    $q->where('equipo_origen_id', $equipoId)
                      ->orWhere('equipo_destino_id', $equipoId);
                });
            } else {
                // Fallback (si el entrenador no tiene equipo asignado)
                $query->where('id', 0);
            }
        }

        return $query;
    }

    public static function form($schema): Schema
    {
        $miEquipoId = auth()->user()->equipoUser?->equipo_id;

        return $schema
            ->components([
                Forms\Components\Select::make('jugador_id')
                    ->label('Jugador a Solicitar')
                    ->options(function () use ($miEquipoId) {
                        // Solo jugadores que tienen equipo y no son del equipo del entrenador logueado
                        return User::role('Jugador')
                            ->whereHas('equipoUser', function ($q) use ($miEquipoId) {
                                if ($miEquipoId) {
                                    $q->where('equipo_id', '!=', $miEquipoId);
                                }
                            })
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jugador.name')
                    ->label('Jugador'),
                TextColumn::make('equipoOrigen.nombre')
                    ->label('Equipo Origen'),
                TextColumn::make('equipoDestino.nombre')
                    ->label('Equipo Destino (Solicitante)'),
                TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'aceptado' => 'success',
                        'rechazado' => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->label('Fecha Solicitud')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('aceptar')
                    ->label('Aceptar Traspaso')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation()
                    // Solo el entrenador del equipo de origen puede aceptar
                    ->visible(fn (Traspaso $record): bool => 
                        $record->estado === 'pendiente' && 
                        (auth()->user()->hasRole('Administrador') || auth()->user()->equipoUser?->equipo_id === $record->equipo_origen_id)
                    )
                    ->action(function (Traspaso $record) {
                        $record->update(['estado' => 'aceptado']);
                        // Actualizar el equipo del jugador
                        if ($record->jugador->equipoUser) {
                            $record->jugador->equipoUser->update(['equipo_id' => $record->equipo_destino_id]);
                        } else {
                            \App\Models\EquipoUser::create([
                                'user_id' => $record->jugador_id,
                                'equipo_id' => $record->equipo_destino_id,
                            ]);
                        }
                    }),
                Action::make('rechazar')
                    ->label('Rechazar')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->requiresConfirmation()
                    // Solo el entrenador del equipo de origen puede rechazar
                    ->visible(fn (Traspaso $record): bool => 
                        $record->estado === 'pendiente' && 
                        (auth()->user()->hasRole('Administrador') || auth()->user()->equipoUser?->equipo_id === $record->equipo_origen_id)
                    )
                    ->action(fn (Traspaso $record) => $record->update(['estado' => 'rechazado'])),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTraspasos::route('/'),
        ];
    }
}
