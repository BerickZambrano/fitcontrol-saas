<?php

namespace App\Filament\Resources\Tenants\Tables;

use App\Mail\TenantApprovedMail;
use App\Mail\TenantRejectedMail;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TenantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre del Club')
                    ->searchable(),
                TextColumn::make('subdominio')
                    ->label('Subdominio')
                    ->searchable(),
                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge(),
                TextColumn::make('nombre_corto')
                    ->label('Nombre Corto')
                    ->searchable(),
                TextColumn::make('nit')
                    ->label('NIT')
                    ->searchable(),
                TextColumn::make('anio_fundacion')
                    ->label('Año de Fundación'),
                TextColumn::make('tipo_club')
                    ->label('Tipo de Club')
                    ->badge(),
                TextColumn::make('escudo_url')
                    ->label('URL del Escudo')
                    ->searchable(),
                TextColumn::make('direccion')
                    ->label('Dirección')
                    ->searchable(),
                TextColumn::make('ciudad')
                    ->label('Ciudad')
                    ->searchable(),
                TextColumn::make('pais')
                    ->label('País')
                    ->searchable(),
                TextColumn::make('email_corporativo')
                    ->label('Correo Corporativo')
                    ->searchable(),
                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(),
                TextColumn::make('sitio_web')
                    ->label('Sitio Web')
                    ->searchable(),
                TextColumn::make('encargado_nombre')
                    ->label('Nombre del Encargado')
                    ->searchable(),
                TextColumn::make('encargado_email')
                    ->label('Correo del Encargado')
                    ->searchable(),
                TextColumn::make('encargado_telefono')
                    ->label('Tel. Encargado')
                    ->searchable(),
                TextColumn::make('rut_document')
                    ->label('Documento RUT')
                    ->searchable(),
                TextColumn::make('camara_comercio')
                    ->label('Cámara de Comercio')
                    ->searchable(),
                TextColumn::make('plan')
                    ->label('Plan')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->iconButton()
                    ->visible(fn ($record) => $record->estado === 'pendiente')
                    ->requiresConfirmation()
                    ->modalHeading('Aprobar solicitud')
                    ->modalDescription('¿Estás seguro de que deseas aprobar esta solicitud de club?')
                    ->modalSubmitActionLabel('Sí, aprobar')
                    ->action(function ($record) {
                        $record->update([
                            'estado'         => 'activo',
                            'register_token' => Str::uuid(),
                        ]);
                        Mail::to($record->encargado_email)
                            ->queue(new TenantApprovedMail($record));
                    }),

                Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->iconButton()
                    ->visible(fn ($record) => $record->estado === 'pendiente')
                    ->requiresConfirmation()
                    ->modalHeading('Rechazar solicitud')
                    ->modalDescription('¿Estás seguro de que deseas rechazar esta solicitud de club?')
                    ->modalSubmitActionLabel('Sí, rechazar')
                    ->action(function ($record) {
                        $record->update(['estado' => 'rechazado']);
                        Mail::to($record->encargado_email)
                            ->queue(new TenantRejectedMail($record));
                    }),

                Action::make('toggleBloqueo')
                    ->label(fn ($record) => $record->estado_pago === 'pendiente' ? 'Desbloquear' : 'Bloquear')
                    ->icon(fn ($record) => $record->estado_pago === 'pendiente' ? 'heroicon-m-lock-closed' : 'heroicon-m-lock-open')
                    ->color(fn ($record) => $record->estado_pago === 'pendiente' ? 'danger' : 'success')
                    ->iconButton()
                    ->requiresConfirmation()
                    ->modalHeading(fn ($record) => $record->estado_pago === 'pendiente' ? 'Desbloquear Club' : 'Bloquear Club')
                    ->modalDescription(fn ($record) => $record->estado_pago === 'pendiente' 
                        ? '¿Estás seguro de que deseas desbloquear este club y habilitar todas sus funciones premium?' 
                        : '¿Estás seguro de que deseas bloquear este club? Se le restringirá el acceso con el paywall.')
                    ->modalSubmitActionLabel(fn ($record) => $record->estado_pago === 'pendiente' ? 'Sí, desbloquear' : 'Sí, bloquear')
                    ->action(function ($record) {
                        $nuevoEstado = $record->estado_pago === 'pendiente' ? 'pagado' : 'pendiente';
                        $record->update(['estado_pago' => $nuevoEstado]);
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    \AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction::make('export')
                        ->label('Exportar seleccionados'),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction::make('export')
                    ->label('Exportar'),
            ]);
    }
}   