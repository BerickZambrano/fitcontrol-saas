<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([

            Forms\Components\TextInput::make('name')
                ->label('Nombre')
                ->required(),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('password')
                ->label('Contraseña')
                ->password()
                ->confirmed()
                ->required(fn (string $operation) => $operation === 'create')
                ->afterStateHydrated(
                    fn ($component) => $component->state(null)
                )
                ->dehydrateStateUsing(
                    fn ($state) => filled($state) ? Hash::make($state) : null
                )
                ->dehydrated(fn ($state) => filled($state)),

            Forms\Components\TextInput::make('password_confirmation')
                ->label('Confirmar contraseña')
                ->password()
                ->dehydrated(false),

            Forms\Components\Select::make('tenant_id')
                ->label('Tenant')
                ->required()
                ->options(
                    fn () => Tenant::pluck('nombre', 'id')
                ),

            Forms\Components\Select::make('roles')
                ->label('Rol')
                ->relationship('roles', 'name')
                ->multiple()
                ->required(),
        ]);
    }
}
