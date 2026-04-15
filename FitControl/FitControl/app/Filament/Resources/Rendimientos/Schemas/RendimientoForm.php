<?php

namespace App\Filament\Resources\Rendimientos\Schemas;

use App\Models\User;
use App\Models\Partido;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Schemas\Components\Section as ComponentsSection;
use Illuminate\Database\Eloquent\Builder;

class RendimientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ComponentsSection::make('Información del jugador')
                    ->columns(2)
                    ->schema([

                        Select::make('user_id')
                            ->label('Jugador')
                            ->options(function () {
                                $query = User::query()->withoutGlobalScopes();
                                if (!auth()->user()->hasRole('super_admin')) {
                                    $query->where('tenant_id', auth()->user()->tenant_id);
                                }
                                return $query->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()

                            ->required(),

                        Select::make('partido_id')
                            ->label('Partido')
                            ->options(function () {
                                $query = Partido::query()->withoutGlobalScopes();
                                if (!auth()->user()->hasRole('super_admin')) {
                                    $query->where('tenant_id', auth()->user()->tenant_id);
                                }
                                return $query->pluck('fecha', 'id');
                            })
                            ->searchable()
                            ->preload()

                            ->required(),
                    ]),

                ComponentsSection::make('Estadísticas')
                    ->columns(3)
                    ->schema([

                        TextInput::make('minutos_jugados')
                            ->label('Minutos jugados')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(120)
                            ->required(),

                        TextInput::make('goles')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        TextInput::make('asistencias')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        TextInput::make('tarjetas_amarillas')
                            ->label('Tarjetas amarillas')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        TextInput::make('tarjetas_rojas')
                            ->label('Tarjetas rojas')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        TextInput::make('evaluacion')
                            ->label('Evaluación')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(10)
                            ->step(0.1)
                            ->required(),
                    ]),
            ]);
    }
}
