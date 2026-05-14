<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\Action as TableAction;
use App\Models\Tenant;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenantApprovedMail;
use App\Mail\TenantRejectedMail;
use Illuminate\Support\Str;
use UnitEnum;

class TenantRequests extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Solicitudes';
    protected static string|UnitEnum|null $navigationGroup = 'Administración';
    protected static ?string $title = 'Solicitudes de Acceso';

    protected string $view = 'filament.pages.tenant-requests';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Tenant::query()->where('estado', 'pendiente')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email_corporativo')
                    ->label('Correo')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha solicitud')
                    ->dateTime(),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Aprobar')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Tenant $record) {

                            $token = Str::uuid();

                            $record->update([
                                'estado' => 'activo',
                                'register_token' => $token,
                            ]);

                            Mail::to($record->email_corporativo)
                                ->queue(new TenantApprovedMail($record));
                        }),

                Action::make('reject')
                    ->label('Rechazar')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Tenant $record) {
                        $record->update([
                            'estado' => 'suspendido',
                        ]);

                        Mail::to($record->email_corporativo)
                            ->queue(new TenantRejectedMail($record));
                    }),
            ]);
    }
}