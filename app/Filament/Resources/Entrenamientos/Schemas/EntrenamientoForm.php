<?php

namespace App\Filament\Resources\Entrenamientos\Schemas;

use App\Models\Equipo;
use App\Models\Instalacion;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Illuminate\Database\Eloquent\Builder;

class EntrenamientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre del Entrenamiento')
                    ->required()
                    ->maxLength(100),

                DatePicker::make('fecha')
                    ->label('Fecha')
                    ->required(),

                TimePicker::make('hora')
                    ->label('Hora')
                    ->required(),

                TextInput::make('ubicacion')
                    ->label('Ubicación')
                    ->required()
                    ->maxLength(100),

                Select::make('equipo_id')
                    ->label('Equipo')
                    ->options(function () {
                        $query = Equipo::query()->withoutGlobalScopes();
                        if (!auth()->user()->hasRole('super_admin')) {
                            $query->where('tenant_id', auth()->user()->tenant_id);
                        }
                        return $query->pluck('nombre', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('instalacion_id')
                    ->label('Instalación')
                    ->options(function () {
                        return Instalacion::query()->pluck('nombre', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->placeholder('Sin instalación específica'),

                Hidden::make('tenant_id'),
            ]);
    }
}
