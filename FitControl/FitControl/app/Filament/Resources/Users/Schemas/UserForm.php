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
        $isSuperAdmin = auth()->check() && auth()->user()->hasRole('super_admin');

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
                ->visible(fn () => $isSuperAdmin)
                ->required($isSuperAdmin)
                ->options(
                    fn () => Tenant::pluck('nombre', 'id')
                )
                ->default(fn () => $isSuperAdmin ? null : auth()->user()->tenant_id)
                ->disabled(fn () => !$isSuperAdmin),

            Forms\Components\Select::make('roles')
                ->label('Rol')
                ->relationship('roles', 'name')
                ->multiple()
                ->required(),
        ]);
    }
}
