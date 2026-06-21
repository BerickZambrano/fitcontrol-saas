<?php

namespace App\Filament\Resources\Tenants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\HtmlString;
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
                Select::make('estado_pago')
                    ->label('Estado de Pago')
                    ->options(['pendiente' => 'Pendiente', 'pagado' => 'Pagado'])
                    ->default('pagado')
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
                Placeholder::make('escudo_url_preview')
                    ->label('Escudo / Logo')
                    ->content(function ($record) {
                        if (!$record || !$record->escudo_url) {
                            return 'No se ha cargado ningún escudo.';
                        }

                        $url = \Illuminate\Support\Facades\Storage::disk('public')->url($record->escudo_url);

                        return new HtmlString("
                            <div style='margin-top: 8px;'>
                                <img src='{$url}' alt='Escudo Preview' style='max-width: 100%; max-height: 200px; border-radius: 8px; border: 1px solid #ccc;' />
                                <a href='{$url}' target='_blank' style='display: inline-block; margin-top: 8px; color: #ff5e00; text-decoration: underline;'>Ver imagen completa</a>
                            </div>
                        ");
                    }),
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
                Placeholder::make('rut_document_preview')
                    ->label('Documento RUT')
                    ->content(function ($record) {
                        if (!$record || !$record->rut_document) {
                            return 'No se ha cargado ningún documento.';
                        }

                        $url = route('admin.tenants.document.show', ['tenant' => $record->id, 'field' => 'rut_document']);
                        $extension = pathinfo($record->rut_document, PATHINFO_EXTENSION);

                        if (in_array(strtolower($extension), ['pdf'])) {
                            return new HtmlString("
                                <div style='margin-top: 8px;'>
                                    <iframe src='{$url}' width='100%' height='500px' style='border: 1px solid #ccc; border-radius: 8px;'></iframe>
                                    <a href='{$url}' target='_blank' style='display: inline-block; margin-top: 8px; color: #ff5e00; text-decoration: underline;'>Abrir PDF en pestaña nueva</a>
                                </div>
                            ");
                        }

                        return new HtmlString("
                            <div style='margin-top: 8px;'>
                                <img src='{$url}' alt='RUT Preview' style='max-width: 100%; max-height: 400px; border-radius: 8px; border: 1px solid #ccc;' />
                                <a href='{$url}' target='_blank' style='display: inline-block; margin-top: 8px; color: #ff5e00; text-decoration: underline;'>Ver imagen completa</a>
                            </div>
                        ");
                    }),

                Placeholder::make('camara_comercio_preview')
                    ->label('Cámara de Comercio')
                    ->content(function ($record) {
                        if (!$record || !$record->camara_comercio) {
                            return 'No se ha cargado ningún documento.';
                        }

                        $url = route('admin.tenants.document.show', ['tenant' => $record->id, 'field' => 'camara_comercio']);
                        $extension = pathinfo($record->camara_comercio, PATHINFO_EXTENSION);

                        if (in_array(strtolower($extension), ['pdf'])) {
                            return new HtmlString("
                                <div style='margin-top: 8px;'>
                                    <iframe src='{$url}' width='100%' height='500px' style='border: 1px solid #ccc; border-radius: 8px;'></iframe>
                                    <a href='{$url}' target='_blank' style='display: inline-block; margin-top: 8px; color: #ff5e00; text-decoration: underline;'>Abrir PDF en pestaña nueva</a>
                                </div>
                            ");
                        }

                        return new HtmlString("
                            <div style='margin-top: 8px;'>
                                <img src='{$url}' alt='Cámara de Comercio Preview' style='max-width: 100%; max-height: 400px; border-radius: 8px; border: 1px solid #ccc;' />
                                <a href='{$url}' target='_blank' style='display: inline-block; margin-top: 8px; color: #ff5e00; text-decoration: underline;'>Ver imagen completa</a>
                            </div>
                        ");
                    }),
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
