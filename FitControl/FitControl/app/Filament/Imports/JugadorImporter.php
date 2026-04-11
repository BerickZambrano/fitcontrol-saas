<?php

namespace App\Filament\Imports;

use App\Models\User;
use App\Models\JugadorPerfil;
use App\Models\Equipo;
use App\Models\EquipoUser;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;

class JugadorImporter extends Importer
{
    protected static ?string $model = User::class;

    public static bool $isQueued = false;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Nombre completo')
                ->rules(['required', 'max:255'])
                ->example('Juan Pérez'),

            ImportColumn::make('email')
                ->label('Correo electrónico')
                ->rules(['nullable', 'email', 'max:255'])
                ->example('juan@ejemplo.com'),

            ImportColumn::make('posicion')
                ->label('Posición')
                ->rules(['nullable', 'in:Portero,Defensa,Mediocampista,Delantero'])
                ->example('Delantero'),

            ImportColumn::make('dorsal')
                ->label('Dorsal')
                ->rules(['nullable', 'integer', 'min:1', 'max:99'])
                ->example('10'),

            ImportColumn::make('pierna_habil')
                ->label('Pierna hábil')
                ->rules(['nullable', 'in:derecha,izquierda,ambas'])
                ->example('derecha'),

            ImportColumn::make('equipo')
                ->label('Equipo (nombre)')
                ->rules(['nullable', 'max:255'])
                ->example('Plantel Principal'),
        ];
    }

    public function resolveRecord(): ?User
    {
        $tenantId = auth()->user()->tenant_id;

        // Check if user with this email already exists in this tenant
        $email = $this->data['email'] ?? null;
        if ($email) {
            $existing = User::where('email', $email)
                ->where('tenant_id', $tenantId)
                ->first();
            if ($existing) {
                return $existing;
            }
        }

        // Create new user
        $user = User::create([
            'tenant_id' => $tenantId,
            'name' => $this->data['name'],
            'email' => $email ?? 'import-' . uniqid() . '@fitcontrol.temp',
            'password' => Hash::make('fitcontrol123'),
        ]);

        $user->assignRole('Jugador');

        // Create JugadorPerfil
        $posicion = $this->data['posicion'] ?? null;
        $dorsal = $this->data['dorsal'] ?? null;
        $pierna = $this->data['pierna_habil'] ?? null;

        if ($posicion || $dorsal || $pierna) {
            JugadorPerfil::create([
                'tenant_id' => $tenantId,
                'user_id' => $user->id,
                'posicion' => $posicion,
                'dorsal' => $dorsal ? (int) $dorsal : null,
                'pierna_habil' => $pierna,
            ]);
        }

        // Link to team if specified
        $equipoNombre = $this->data['equipo'] ?? null;
        if ($equipoNombre) {
            $equipo = Equipo::where('nombre', $equipoNombre)
                ->where('tenant_id', $tenantId)
                ->first();

            if ($equipo) {
                EquipoUser::firstOrCreate([
                    'tenant_id' => $tenantId,
                    'equipo_id' => $equipo->id,
                    'user_id' => $user->id,
                ], [
                    'fecha_inicio' => now()->toDateString(),
                ]);
            }
        }

        return $user;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Se importaron ' . number_format($import->successful_rows) . ' jugadores correctamente.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' filas fallaron.';
        }

        return $body;
    }
}
