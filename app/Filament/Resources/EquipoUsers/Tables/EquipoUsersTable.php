<?php

namespace App\Filament\Resources\EquipoUsers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class EquipoUsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $user = auth()->user();
                $query->withoutGlobalScopes()->with([
                    'equipo' => function ($q) {
                        $q->withoutGlobalScopes();
                    },
                    'jugador' => function ($q) {
                        $q->withoutGlobalScopes();
                    },
                ]);
                if (!$user->hasRole('super_admin')) {
                    $query->where('equipo_user.tenant_id', $user->tenant_id);
                }
                return $query;
            })
            ->columns([
                Tables\Columns\TextColumn::make('equipo.nombre')
                    ->label('Equipo')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('jugador.name')
                    ->label('Miembro')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Fecha de Inicio')
                    ->date(),

                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fecha de Fin')
                    ->date(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
             ->filters([

            SelectFilter::make('equipo_id')
                ->label('Equipo')
                ->relationship('equipo', 'nombre')
                ->searchable()
                ->preload(),

            SelectFilter::make('jugador_id')
                ->label('Miembro')
                ->relationship('jugador', 'name')
                ->searchable()
                ->preload(),

            Filter::make('fecha_inicio')
                ->label('Fecha de Inicio')
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
                            fn ($q) => $q->whereDate('fecha_inicio', '>=', $data['desde'])
                        )
                        ->when(
                            $data['hasta'],
                            fn ($q) => $q->whereDate('fecha_inicio', '<=', $data['hasta'])
                        );
                }),

            Filter::make('activos')
                ->label('Solo miembros activos')
                ->query(fn ($query) => $query->whereNull('fecha_fin')),

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

