<?php

namespace App\Filament\Imports;

use App\Models\Torneo;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TorneoImporter extends Importer
{
    protected static ?string $model = Torneo::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nombre')
                ->label('Nombre del Torneo')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('categoria')
                ->label('Categoría')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('fecha_inicio')
                ->label('Fecha de Inicio (Y-m-d)')
                ->rules(['nullable', 'date']),
            ImportColumn::make('fecha_fin')
                ->label('Fecha de Fin (Y-m-d)')
                ->rules(['nullable', 'date']),
            ImportColumn::make('estado')
                ->label('Estado (activo, en_progreso, finalizado)')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('tenant_id')
                ->label('ID del Tenant (Opcional)')
                ->numeric()
                ->rules(['nullable', 'integer']),
        ];
    }

    public function resolveRecord(): ?Torneo
    {
        return Torneo::firstOrNew([
            'nombre' => $this->data['nombre'],
        ]);
    }

    protected function beforeSave(): void
    {
        $tenantId = filament()->getTenant()?->id ?? auth()->user()?->tenant_id;
        if ($tenantId) {
            $this->record->tenant_id = $tenantId;
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Se importaron ' . number_format($import->successful_rows) . ' ' . str('torneo')->plural($import->successful_rows) . '.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' fallaron.';
        }

        return $body;
    }
}
