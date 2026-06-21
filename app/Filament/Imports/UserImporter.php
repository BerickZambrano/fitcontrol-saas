<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Nombre')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->label('Correo Electrónico')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('password')
                ->label('Contraseña')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('rol')
                ->label('Rol del Usuario')
                ->rules(['nullable', 'string'])
                ->fillRecordUsing(function ($record, $state) {
                    // Prevenir que intente guardar en la DB directamente.
                }),
            ImportColumn::make('tenant_id')
                ->label('ID del Tenant (Opcional)')
                ->numeric()
                ->rules(['nullable', 'integer']),
        ];
    }

    public function resolveRecord(): ?User
    {
        // En lugar de devolver null, verificamos si existe por email para actualizar, o creamos nuevo
        return User::firstOrNew([
            'email' => $this->data['email'],
        ]);
    }

    protected function beforeSave(): void
    {
        if (isset($this->data['password']) && !empty($this->data['password'])) {
            $this->record->password = Hash::make($this->data['password']);
        }
        
        // Auto-asignar el tenant_id del usuario que está importando
        $tenantId = filament()->getTenant()?->id ?? auth()->user()?->tenant_id;
        if ($tenantId) {
            $this->record->tenant_id = $tenantId;
        }
    }

    protected function afterSave(): void
    {
        // Asignar el rol al usuario después de guardarlo
        if (isset($this->data['rol']) && !empty($this->data['rol'])) {
            $this->record->assignRole($this->data['rol']);
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Se importaron ' . number_format($import->successful_rows) . ' ' . str('usuario')->plural($import->successful_rows) . '.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' fallaron.';
        }

        return $body;
    }
}
