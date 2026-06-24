<?php

namespace App\Filament\Resources\HistorialMedicos\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section as ComponentsSection;
use Illuminate\Database\Eloquent\Builder;

class HistorialMedicoForm
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
                                $query = User::query()
                                    ->withoutGlobalScopes()
                                    ->where(function ($q) {
                                        $q->role('Jugador')
                                          ->orWhereHas('jugadorPerfil');
                                    });
                                if (!auth()->user()->hasRole('super_admin')) {
                                    $query->where('tenant_id', auth()->user()->tenant_id);
                                }
                                return $query->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('tipo_lesion')
                            ->label('Tipo')
                            ->options([
                                'lesion' => 'Lesión',
                                'enfermedad' => 'Enfermedad',
                                'control' => 'Control',
                            ])
                            ->required(),
                    ]),

                ComponentsSection::make('Detalle Médico')
                    ->schema([

                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(4)
                        ->maxLength(1000)
                            ->required(),

                        Select::make('gravedad')
                            ->label('Gravedad')
                            ->options([
                                'leve' => 'Leve',
                                'media' => 'Media',
                                'grave' => 'Grave',
                            ])
                            ->required(),

                        Toggle::make('apto')
                            ->label('Apto para jugar')
                            ->default(false),
                    ]),

                ComponentsSection::make('Fechas')
                    ->columns(2)
                    ->schema([

                        DatePicker::make('fecha_inicio')
                            ->label('Fecha de Inicio')
                            ->required(),

                        DatePicker::make('fecha_fin')
                            ->label('Fecha de Fin')
                            ->afterOrEqual('fecha_inicio')
                            ->validationMessages(['after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.']),
                    ]),
            ]);
    }
}
