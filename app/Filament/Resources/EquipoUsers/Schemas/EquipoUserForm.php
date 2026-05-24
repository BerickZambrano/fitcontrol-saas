<?php

namespace App\Filament\Resources\EquipoUsers\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;
use App\Models\Equipo;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class EquipoUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Select::make('equipo_id')
                ->label('Equipo')
                ->required()
                ->options(function () {
                    $query = Equipo::query()->withoutGlobalScopes();
                    if (!auth()->user()->hasRole('super_admin')) {
                        $query->where('tenant_id', auth()->user()->tenant_id);
                    }
                    return $query->pluck('nombre', 'id');
                })
                ->searchable()
                ->preload()
                ->relationship(
                    'equipo',
                    'nombre',

                ),

            Forms\Components\Select::make('user_id')
                ->label('Miembro (Jugador o Entrenador)')
                ->required()
                ->options(function () {
                    $query = User::query()
                        ->withoutGlobalScopes()
                        ->whereHas('roles', function ($q) {
                            $q->whereIn('name', ['Jugador', 'Entrenador']);
                        });

                    if (!auth()->user()->hasRole('super_admin')) {
                        $query->where('tenant_id', auth()->user()->tenant_id);
                    }

                    return $query->pluck('name', 'id');
                })
                ->searchable()
                ->preload()
                ->relationship(
                    'jugador',
                    'name',
                    modifyQueryUsing: function (Builder $query) {
                        $query->withoutGlobalScopes()
                            ->whereHas('roles', function ($q) {
                                $q->whereIn('name', ['Jugador', 'Entrenador']);
                            });
                        if (!auth()->user()->hasRole('super_admin')) {
                            $query->where('tenant_id', auth()->user()->tenant_id);
                        }
                        return $query;
                    }
                ),

            Forms\Components\DatePicker::make('fecha_inicio')
                ->label('Fecha de Inicio')
                ->required(),

            Forms\Components\DatePicker::make('fecha_fin')
                ->label('Fecha de Fin')
                ->after('fecha_inicio')
                ->validationMessages(['after' => 'La fecha de fin debe ser posterior a la fecha de inicio.'])
                ->nullable(),
        ]);
    }
}
