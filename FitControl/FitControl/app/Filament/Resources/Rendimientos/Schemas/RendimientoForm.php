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
                            ->label('Minutos Jugados')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(120)
                            ->validationMessages(['max' => 'Los minutos jugados no pueden superar 120.'])
                            ->required(),

                        TextInput::make('goles')
                            ->label('Goles')
                            ->numeric()
                            ->default(0)
                            ->maxValue(100)
                            ->minValue(0),

                        TextInput::make('asistencias')
                            ->label('Asistencias')
                            ->numeric()
                            ->default(0)
                            ->maxValue(100)
                            ->minValue(0),

                        TextInput::make('tarjetas_amarillas')
                            ->label('Tarjetas Amarillas')
                            ->numeric()
                            ->default(0)
                            ->maxValue(2)
                            ->minValue(0),

                        TextInput::make('tarjetas_rojas')
                            ->label('Tarjetas Rojas')
                            ->numeric()
                            ->default(0)
                            ->maxValue(1)
                            ->minValue(0),

                        TextInput::make('evaluacion')
                            ->label('Evaluación')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(10)
                            ->validationMessages([
                                'max' => 'La evaluación no puede ser mayor a 10',
                                'min' => 'La evaluación no puede ser menor a 0'
                            ])
                            ->step(0.1)
                            ->required(),
                    ]),
            ]);
    }
}
