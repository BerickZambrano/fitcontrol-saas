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
                    ->searchable(),
                TextColumn::make('subdominio')
                    ->searchable(),
                TextColumn::make('estado')
                    ->badge(),
                TextColumn::make('nombre_corto')
                    ->searchable(),
                TextColumn::make('nit')
                    ->searchable(),
                TextColumn::make('anio_fundacion'),
                TextColumn::make('tipo_club')
                    ->badge(),
                TextColumn::make('escudo_url')
                    ->searchable(),
                TextColumn::make('direccion')
                    ->searchable(),
                TextColumn::make('ciudad')
                    ->searchable(),
                TextColumn::make('pais')
                    ->searchable(),
                TextColumn::make('email_corporativo')
                    ->searchable(),
                TextColumn::make('telefono')
                    ->searchable(),
                TextColumn::make('sitio_web')
                    ->searchable(),
                TextColumn::make('encargado_nombre')
                    ->searchable(),
                TextColumn::make('encargado_email')
                    ->searchable(),
                TextColumn::make('encargado_telefono')
                    ->searchable(),
                TextColumn::make('rut_document')
                    ->searchable(),
                TextColumn::make('camara_comercio')
                    ->searchable(),
                TextColumn::make('plan')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}   