<?php

namespace App\Filament\Imports;

use App\Models\Equipo;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class EquipoImporter extends Importer
{
    protected static ?string $model = Equipo::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nombre')
                ->label('Nombre del Equipo')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('categoria')
                ->label('Categoría (Masculino, Femenino, Mixto)')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('categoria_equipo')
                ->label('Subcategoría (Sub-10, Profesional, etc)')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('ubi_equipo')
                ->label('Ubicación')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('contacto_equipo')
                ->label('Contacto')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('tenant_id')
                ->label('ID del Tenant (Opcional)')
                ->numeric()
                ->rules(['nullable', 'integer']),
        ];
    }

    public function resolveRecord(): ?Equipo
    {
        return Equipo::firstOrNew([
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
        $body = 'Se importaron ' . number_format($import->successful_rows) . ' ' . str('equipo')->plural($import->successful_rows) . '.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' fallaron.';
        }

        return $body;
    }
}
