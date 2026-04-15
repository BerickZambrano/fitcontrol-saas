<?php

namespace App\Filament\Resources\AsistenciaEntrenamientos\Schemas;

use App\Models\Entrenamiento;
use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class AsistenciaEntrenamientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                Select::make('entrenamiento_id')
                                    ->label('Entrenamiento')
                                    ->options(function () {
                                        $query = Entrenamiento::query()->withoutGlobalScopes();
                                        if (!auth()->user()->hasRole('super_admin')) {
                                            $query->where('tenant_id', auth()->user()->tenant_id);
                                        }
                                        return $query->pluck('fecha', 'id');
                                    })
                                    ->searchable()
                                    ->preload()

                                    ->required(),

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

                                Toggle::make('presente')
                                    ->label('Presente')
                                    ->default(false),

                                Hidden::make('tenant_id')
                                    ->default(Filament::getTenant()?->id)
                                    ->dehydrated(fn (): bool => true)
                                    ->visible(false),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 2,
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
