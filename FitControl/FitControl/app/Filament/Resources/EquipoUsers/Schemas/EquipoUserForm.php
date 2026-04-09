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
                    modifyQueryUsing: fn (Builder $query) => $query->withoutGlobalScopes()
                ),

            Forms\Components\Select::make('user_id')
                ->label('Jugador')
                ->required()
                ->options(function () {
                    $query = User::query()
                        ->withoutGlobalScopes()
                        ->whereHas('roles', function ($q) {
                            $q->where('name', 'jugador');
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
                    modifyQueryUsing: fn (Builder $query) => $query->withoutGlobalScopes()
                ),

            Forms\Components\DatePicker::make('fecha_inicio')
                ->label('Fecha de Inicio')
                ->required(),

            Forms\Components\DatePicker::make('fecha_fin')
                ->label('Fecha de Fin')
                ->nullable(),
        ]);
    }
}
