<?php

namespace App\Filament\Resources\Tenants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('subdominio')
                    ->default(null),
                Select::make('estado')
                    ->options(['activo' => 'Activo', 'suspendido' => 'Suspendido', 'pendiente' => 'Pendiente'])
                    ->default('pendiente')
                    ->required(),
                TextInput::make('nombre_corto')
                    ->default(null),
                TextInput::make('nit')
                    ->required(),
                TextInput::make('anio_fundacion')
                    ->default(null),
                Select::make('tipo_club')
                    ->options(['formativo' => 'Formativo', 'amateur' => 'Amateur', 'profesional' => 'Profesional'])
                    ->required(),
                Textarea::make('colores_oficiales')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('escudo_url')
                    ->url()
                    ->default(null),
                TextInput::make('direccion')
                    ->default(null),
                TextInput::make('ciudad')
                    ->required(),
                TextInput::make('pais')
                    ->required(),
                TextInput::make('email_corporativo')
                    ->email()
                    ->required(),
                TextInput::make('telefono')
                    ->tel()
                    ->default(null),
                TextInput::make('sitio_web')
                    ->default(null),
                TextInput::make('encargado_nombre')
                    ->required(),
                TextInput::make('encargado_email')
                    ->email()
                    ->default(null),
                TextInput::make('encargado_telefono')
                    ->tel()
                    ->default(null),
                TextInput::make('rut_document')
                    ->default(null),
                TextInput::make('camara_comercio')
                    ->default(null),
                Select::make('plan')
                    ->options(['mensual' => 'Mensual', 'anual' => 'Anual'])
                    ->default(null),
                Textarea::make('rejection_reason')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
