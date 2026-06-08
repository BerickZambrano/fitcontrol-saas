<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\Convocatoria;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\Action;

class ConvocatoriasPendientesWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Convocatoria::query()
                    ->with([
                        'partido' => fn ($q) => $q->withoutGlobalScopes(),
                        'equipo' => fn ($q) => $q->withoutGlobalScopes(),
                    ])
                    ->where('jugador_id', auth()->id())
                    ->where('estado_asistencia', 'convocado')
            )
            ->heading('Convocatorias Pendientes')
            ->description('Debes confirmar tu asistencia para poder ser considerado en la alineación del partido.')
            ->columns([
                Tables\Columns\TextColumn::make('partido.fecha')
                    ->label('Fecha del Partido')
                    ->date(),
                Tables\Columns\TextColumn::make('partido.hora')
                    ->label('Hora')
                    ->time(),
                Tables\Columns\TextColumn::make('equipo.nombre')
                    ->label('Equipo'),
            ])
            ->actions([
                Action::make('confirmar')
                    ->label('Confirmar Asistencia')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->action(fn (Convocatoria $record) => $record->update(['estado_asistencia' => 'confirmado'])),
                Action::make('rechazar')
                    ->label('Rechazar')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->action(fn (Convocatoria $record) => $record->update(['estado_asistencia' => 'rechazado'])),
            ]);
    }
}
