<?php

namespace App\Filament\Resources\JugadorPerfils\Schemas;

use Filament\Schemas\Schema;

class JugadorPerfilForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Usuario vinculado')
                    ->searchable()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Este usuario ya tiene un perfil de jugador asignado.',
                    ])
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Nombre completo')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->unique(table: 'users', column: 'email')
                            ->validationMessages([
                                'unique' => 'Este correo electrónico ya está registrado en el sistema.',
                            ])
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $user = \App\Models\User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
                            'tenant_id' => auth()->user()->tenant_id,
                        ]);
                        $user->assignRole('Jugador');
                        return $user->id;
                    }),
                \Filament\Forms\Components\Select::make('posicion')
                    ->label('Posición')
                    ->options([
                        'Portero' => 'Portero',
                        'Defensa' => 'Defensa',
                        'Mediocampista' => 'Mediocampista',
                        'Delantero' => 'Delantero',
                    ]),
                \Filament\Forms\Components\TextInput::make('dorsal')
                    ->label('Dorsal')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(99),
                \Filament\Forms\Components\TextInput::make('altura')
                    ->label('Altura (m)')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0.5)
                    ->maxValue(3.0)
                    ->validationMessages([
                        'max' => 'La altura no puede ser mayor a 3 metros.',
                    ]),
                \Filament\Forms\Components\TextInput::make('peso')
                    ->label('Peso (kg)')
                    ->numeric()
                    ->step(0.1)
                    ->minValue(20)
                    ->maxValue(300)
                    ->validationMessages([
                        'max' => 'El peso no puede ser mayor a 300 kg.',
                    ]),
                \Filament\Forms\Components\Select::make('pierna_habil')
                    ->label('Pierna hábil')
                    ->options([
                        'derecha' => 'Derecha',
                        'izquierda' => 'Izquierda',
                        'ambas' => 'Ambas',
                    ]),
            ]);
    }
}
