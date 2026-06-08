<?php

namespace App\Filament\Resources\Partidos\Schemas;

use App\Models\Equipo;
use App\Models\Torneo;
use App\Models\Instalacion;
use Filament\Forms;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class PartidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\DatePicker::make('fecha')
                ->label('Fecha')
                ->required(),

            Forms\Components\TimePicker::make('hora')
                ->label('Hora')
                ->required(),

            Forms\Components\Select::make('equipo_local_id')
                ->label('Equipo Local')
                ->required()
                ->options(function () {
                    $query = Equipo::query()->withoutGlobalScopes();
                    if (!auth()->user()->hasRole('super_admin')) {
                        $query->where('tenant_id', auth()->user()->tenant_id);
                    }
                    return $query->pluck('nombre', 'id');
                })
                ->searchable()
                ->preload(),


            Forms\Components\Select::make('equipo_visitante_id')
                ->label('Equipo Visitante')
                ->required()
                ->options(function () {
                    $query = \App\Models\Equipo::query()->withoutGlobalScopes();
                    if (!auth()->user()->hasRole('super_admin')) {
                        $query->where('tenant_id', auth()->user()->tenant_id);
                    }
                    return $query->pluck('nombre', 'id');
                })
                ->searchable()
                ->preload(),




            Forms\Components\Select::make('fase')
                ->label('Fase del Torneo')
                ->nullable()
                ->placeholder('Sin fase asignada')
                ->options([
                    'grupo'      => 'Fase de Grupos',
                    'octavos'    => 'Octavos de Final',
                    'cuartos'    => 'Cuartos de Final',
                    'semifinal'  => 'Semifinal',
                    'final'      => 'Final',
                    'amistoso'   => 'Amistoso',
                ]),

            Forms\Components\Select::make('torneo_id')
                ->label('Torneo')
                ->required()
                ->options(function () {
                    $query = Torneo::query()->withoutGlobalScopes();
                    if (!auth()->user()->hasRole('super_admin')) {
                        $query->where('tenant_id', auth()->user()->tenant_id);
                    }
                    return $query->pluck('nombre', 'id');
                })
                ->searchable()
                ->preload(),

            Forms\Components\Select::make('instalacion_id')
                ->label('Instalación')
                ->options(function () {
                    return Instalacion::query()->pluck('nombre', 'id');
                })
                ->searchable()
                ->preload()
                ->nullable()
                ->placeholder('Sin instalación específica'),

            Forms\Components\Select::make('arbitro_id')
                ->label('Árbitro Asignado')
                ->options(function () {
                    $query = \App\Models\User::role('Arbitro');
                    if (!auth()->user()->hasRole('super_admin')) {
                        $query->where('tenant_id', auth()->user()->tenant_id);
                    }
                    return $query->pluck('name', 'id');
                })
                ->searchable()
                ->preload()
                ->nullable()
                ->placeholder('Sin árbitro asignado'),


            Forms\Components\Hidden::make('tenant_id')
                ->default(fn () => auth()->user()->tenant_id)
                ->required(),
        ]);
    }
}
