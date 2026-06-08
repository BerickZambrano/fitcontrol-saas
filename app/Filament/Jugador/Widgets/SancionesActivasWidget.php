<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\Sancion;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class SancionesActivasWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Sancion::query()
                    ->where('jugador_id', auth()->id())
                    ->where('estado', 'activa')
            )
            ->heading('⚠️ Sanciones Activas')
            ->description('No podrás ser seleccionado para jugar mientras tengas una sanción activa.')
            ->columns([
                Tables\Columns\TextColumn::make('partidoOrigen.fecha')
                    ->label('Fecha del Partido')
                    ->date(),
                Tables\Columns\TextColumn::make('cantidad_partidos_suspension')
                    ->label('Suspensión Total (Partidos)')
                    ->badge()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('partidos_cumplidos')
                    ->label('Partidos Cumplidos')
                    ->badge()
                    ->color('warning'),
            ]);
    }
}
