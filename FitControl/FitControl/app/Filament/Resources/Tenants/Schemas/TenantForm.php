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
                    ->label('Nombre del Club')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('subdominio')
                    ->label('Subdominio')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->validationMessages(['unique' => 'Este subdominio ya está en uso.'])
                    ->default(null),
                Select::make('estado')
                    ->label('Estado')
                    ->options(['activo' => 'Activo', 'suspendido' => 'Suspendido', 'pendiente' => 'Pendiente'])
                    ->default('pendiente')
                    ->required(),
                TextInput::make('nombre_corto')
                    ->label('Nombre Corto')
                    ->maxLength(50)
                    ->default(null),
                TextInput::make('nit')
                    ->label('NIT')
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->validationMessages(['unique' => 'Este NIT ya está registrado.'])
                    ->required(),
                TextInput::make('anio_fundacion')
                    ->label('Año de Fundación')
                    ->numeric()
                    ->minValue(1800)
                    ->maxValue(date('Y'))
                    ->default(null),
                Select::make('tipo_club')
                    ->label('Tipo de Club')
                    ->options(['formativo' => 'Formativo', 'amateur' => 'Amateur', 'profesional' => 'Profesional'])
                    ->required(),
                Textarea::make('colores_oficiales')
                    ->label('Colores Oficiales')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('escudo_url')
                    ->label('URL del Escudo')
                    ->url()
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('direccion')
                    ->label('Dirección')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('ciudad')
                    ->label('Ciudad')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('pais')
                    ->label('País')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('email_corporativo')
                    ->label('Correo Corporativo')
                    ->email()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->validationMessages(['unique' => 'Este correo corporativo ya está en uso.'])
                    ->required(),
                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->maxLength(50)
                    ->tel()
                    ->default(null),
                TextInput::make('sitio_web')
                    ->label('Sitio Web')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('encargado_nombre')
                    ->label('Nombre del Encargado')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('encargado_email')
                    ->label('Correo del Encargado')
                    ->email()
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('encargado_telefono')
                    ->label('Teléfono del Encargado')
                    ->maxLength(50)
                    ->tel()
                    ->default(null),
                TextInput::make('rut_document')
                    ->label('Documento RUT (URL)')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('camara_comercio')
                    ->label('Cámara de Comercio (URL)')
                    ->maxLength(255)
                    ->default(null),
                Select::make('plan')
                    ->label('Plan')
                    ->options(['mensual' => 'Mensual', 'anual' => 'Anual'])
                    ->default(null),
                Textarea::make('rejection_reason')
                    ->label('Motivo de Rechazo')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
