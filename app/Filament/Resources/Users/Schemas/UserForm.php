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
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label('Correo Electrónico')
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'unique' => 'Este correo electrónico ya está registrado en el sistema.',
                ]),

            Forms\Components\TextInput::make('password')
                ->label('Contraseña')
                ->password()
                ->revealable()
                ->confirmed()
                ->minLength(8)
                ->maxLength(255)
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
                ->revealable()
                ->dehydrated(false),

            Forms\Components\Select::make('tenant_id')
                ->label('Tenant')
                ->relationship('tenant', 'nombre')
                ->visible(fn () => $isSuperAdmin)
                ->required($isSuperAdmin)
                ->default(fn () => $isSuperAdmin ? null : auth()->user()->tenant_id)
                ->disabled(fn () => !$isSuperAdmin)
                ->createOptionForm([
                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('nombre')
                                ->label('Nombre del Club')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('nit')
                                ->label('NIT')
                                ->required()
                                ->maxLength(50)
                                ->unique(table: 'tenants', column: 'nit')
                                ->validationMessages(['unique' => 'Este NIT ya está registrado.']),
                            Forms\Components\Select::make('tipo_club')
                                ->label('Tipo de Club')
                                ->options(['formativo' => 'Formativo', 'amateur' => 'Amateur', 'profesional' => 'Profesional'])
                                ->required(),
                            Forms\Components\Select::make('estado')
                                ->label('Estado')
                                ->options(['activo' => 'Activo', 'suspendido' => 'Suspendido', 'pendiente' => 'Pendiente'])
                                ->default('activo')
                                ->required(),
                            Forms\Components\TextInput::make('ciudad')
                                ->label('Ciudad')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('pais')
                                ->label('País')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email_corporativo')
                                ->label('Correo Corporativo')
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->unique(table: 'tenants', column: 'email_corporativo')
                                ->validationMessages(['unique' => 'Este correo corporativo ya está en uso.']),
                            Forms\Components\TextInput::make('encargado_nombre')
                                ->label('Nombre del Encargado')
                                ->required()
                                ->maxLength(255),
                        ]),
                ])
                ->createOptionUsing(function (array $data) {
                    $tenant = \App\Models\Tenant::create([
                        'nombre' => $data['nombre'],
                        'nit' => $data['nit'],
                        'tipo_club' => $data['tipo_club'],
                        'estado' => $data['estado'],
                        'ciudad' => $data['ciudad'],
                        'pais' => $data['pais'],
                        'email_corporativo' => $data['email_corporativo'],
                        'encargado_nombre' => $data['encargado_nombre'],
                    ]);
                    return $tenant->id;
                }),

            Forms\Components\Select::make('roles')
                ->label('Roles')
                ->relationship(
                    name: 'roles',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn ($query) => auth()->user()->hasRole('super_admin') 
                        ? $query 
                        : $query->where('name', '!=', 'super_admin')
                )
                ->multiple()
                ->preload()
                ->required(),

            Forms\Components\Select::make('two_factor_type')
                ->label('Autenticación de Dos Factores')
                ->options([
                    'none' => 'Desactivado',
                    'email' => 'Correo Electrónico',
                ])
                ->default('none')
                ->required(),
        ]);
    }
}
