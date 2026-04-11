<?php

namespace App\Filament\Resources\Equipos\Schemas;

use Filament\Forms;
// use Filament\Forms\Components\Actions\Action; // TODO: Fix - Class not found
use Filament\Schemas\Schema;

class EquipoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre del Equipo')
                    ->required(),

                Forms\Components\Select::make('categoria')
                    ->label('Categoría')
                    ->options([
                        'profesional' => 'Profesional',
                        'amateur' => 'Amateur',
                        'formativo' => 'Formativo',
                    ])
                    ->required(),

                Forms\Components\FileUpload::make('logo_equipo')
                    ->label('Logo del Equipo')
                    ->disk('public')
                    ->image(),

                Forms\Components\TextInput::make('ubi_equipo')
                    ->label('Ubicación'),

                Forms\Components\TextInput::make('contacto_equipo')
                    ->label('Contacto'),

                // Asignar tenant_id automáticamente
                Forms\Components\Hidden::make('tenant_id')
                    ->default(fn () => auth()->user()->tenant_id),

                Forms\Components\Checkbox::make('acepta_terminos')
                    ->label('')
                    ->helperText('Acepto los Términos y Condiciones')
                    // TODO: Re-enable suffixAction when Action class is available
                    // ->suffixAction(
                    //     Action::make('ver_terminos')
                    //         ->label('Términos y Condiciones')
                    //         ->icon('heroicon-o-document-text')
                    //         ->color('primary')
                    //         ->modalHeading('Términos y Condiciones')
                    //         ->modalContent(fn (): string => '
                    //             <div class="prose prose-sm max-w-none">
                    //                 <h3>1. Aceptación del Servicio</h3>
                    //                 <p>Al registrar un equipo en nuestra plataforma, usted acepta cumplir con todas las políticas y regulaciones establecidas.</p>
                    //
                    //                 <h3>2. Responsabilidades del Usuario</h3>
                    //                 <p>El usuario se compromete a proporcionar información veraz y mantenerla actualizada. Es responsable del uso que se le dé a la cuenta del equipo.</p>
                    //
                    //                 <h3>3. Uso Adecuado</h3>
                    //                 <p>Queda prohibido el uso de la plataforma para actividades ilícitas, fraudulentas o que puedan dañar a terceros.</p>
                    //
                    //                 <h3>4. Propiedad Intelectual</h3>
                    //                 <p>Todo el contenido generado dentro de la plataforma está sujeto a las políticas de propiedad intelectual establecidas.</p>
                    //
                    //                 <h3>5. Privacidad</h3>
                    //                 <p>Los datos personales serán tratados conforme a nuestra política de privacidad vigente.</p>
                    //
                    //                 <h3>6. Modificaciones</h3>
                    //                 <p>Nos reservamos el derecho de modificar estos términos en cualquier momento, notificando a los usuarios de manera oportuna.</p>
                    //
                    //                 <h3>7. Limitación de Responsabilidad</h3>
                    //                 <p>La plataforma no se hace responsable por daños directos o indirectos derivados del uso del servicio.</p>
                    //
                    //                 <h3>8. Contacto</h3>
                    //                 <p>Para cualquier consulta sobre estos términos, puede contactarnos a través de nuestros canales oficiales.</p>
                    //             </div>
                    //         ')
                    //         ->modalWidth('4xl')
                    //         ->modalSubmitAction(false)
                    //         ->modalCancelAction(fn (Action $action) => $action->label('Cerrar')),
                    // )
                    ->required()
                    ->validationMessages([
                        'accepted' => 'Debes aceptar los Términos y Condiciones para continuar.',
                    ]),
            ]);
    }
}
