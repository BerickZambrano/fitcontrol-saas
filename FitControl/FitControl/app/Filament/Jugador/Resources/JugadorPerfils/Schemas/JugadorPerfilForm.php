<?php

namespace App\Filament\Jugador\Resources\JugadorPerfils\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section as ComponentsSection;
use Illuminate\Database\Eloquent\Builder;

class JugadorPerfilForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                ComponentsSection::make('Jugador')
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
                            ->modifyQueryUsing(function (Builder $query) {
                                $query->withoutGlobalScopes();
                                if (!auth()->user()->hasRole('super_admin')) {
                                    $query->where('tenant_id', auth()->user()->tenant_id);
                                }
                                return $query;
                            })
                            ->required(),

                        TextInput::make('dorsal')
                            ->label('Dorsal')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(99)
                            ->required(),
                    ]),

                ComponentsSection::make('Datos del perfil')
                    ->columns(3)
                    ->schema([

                        Select::make('posicion')
                            ->label('Posición')
                            ->options([
                                'portero' => 'Portero',
                                'defensa' => 'Defensa',
                                'mediocampo' => 'Mediocampo',
                                'delantero' => 'Delantero',
                            ])
                            ->required(),

                        TextInput::make('altura')
                            ->label('Altura (cm)')
                            ->numeric()
                            ->minValue(100)
                            ->maxValue(230),

                        TextInput::make('peso')
                            ->label('Peso (kg)')
                            ->numeric()
                            ->minValue(30)
                            ->maxValue(150),

                        Select::make('pierna_habil')
                            ->label('Pierna hábil')
                            ->options([
                                'derecha' => 'Derecha',
                                'izquierda' => 'Izquierda',
                                'ambas' => 'Ambas',
                            ])
                            ->required(),
                    ]),
            ]);
    }
}
