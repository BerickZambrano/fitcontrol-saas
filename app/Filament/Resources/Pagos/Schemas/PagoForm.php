<?php

namespace App\Filament\Resources\Pagos\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;
use App\Models\User;

class PagoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Select::make('user_id')
                ->label('Usuario')
                ->required()
                ->options(function () {
                    // Solo usuarios de su tenant
                    return User::where('tenant_id', auth()->user()->tenant_id)
                        ->pluck('name', 'id');
                })
                ->searchable(),

            Forms\Components\DatePicker::make('fecha')
                ->label('Fecha')
                ->required(),

            Forms\Components\TextInput::make('monto')
                ->label('Monto')
                ->numeric()
                ->minValue(0)
                ->maxValue(99999999.99)
                ->validationMessages([
                    'max' => 'El monto ingresado es demasiado grande.',
                    'min' => 'El monto no puede ser negativo.',
                ])
                ->prefix('$')
                ->required(),

            Forms\Components\Select::make('estado')
                ->label('Estado')
                ->required()
                ->options([
                    'pendiente' => 'Pendiente',
                    'pagado' => 'Pagado',
                    'rechazado' => 'Rechazado',
                ]),

           
        ]);
    }
}
