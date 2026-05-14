<?php

namespace App\Filament\Imports;

use App\Models\Instalacion;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class InstalacionImporter extends Importer
{
    protected static ?string $model = Instalacion::class;

    public static bool $isQueued = false;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nombre')
                ->label('Nombre')
                ->rules(['required', 'max:255'])
                ->example('Cancha Principal'),

            ImportColumn::make('tipo')
                ->label('Tipo')
                ->rules(['required', 'max:100'])
                ->example('Cancha de fútbol'),

            ImportColumn::make('ubicacion')
                ->label('Ubicación')
                ->rules(['required', 'max:255'])
                ->example('Zona Norte'),

            ImportColumn::make('capacidad')
                ->label('Capacidad')
                ->rules(['required', 'integer', 'min:1'])
                ->example('22'),

            ImportColumn::make('estado')
                ->label('Estado')
                ->rules(['required', 'in:disponible,ocupada,mantenimiento'])
                ->example('disponible'),
        ];
    }

    public function resolveRecord(): ?Instalacion
    {
        return Instalacion::firstOrNew([
            'nombre' => $this->data['nombre'],
            'tenant_id' => auth()->user()->tenant_id,
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Se importaron ' . number_format($import->successful_rows) . ' instalaciones correctamente.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' filas fallaron.';
        }

        return $body;
    }
}
