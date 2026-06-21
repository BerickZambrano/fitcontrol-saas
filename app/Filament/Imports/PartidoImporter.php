<?php

namespace App\Filament\Imports;

use App\Models\Partido;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PartidoImporter extends Importer
{
    protected static ?string $model = Partido::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('local_id')
                ->label('ID Equipo Local')
                ->numeric()
                ->requiredMapping()
                ->rules(['required', 'integer']),
            ImportColumn::make('visitante_id')
                ->label('ID Equipo Visitante')
                ->numeric()
                ->requiredMapping()
                ->rules(['required', 'integer']),
            ImportColumn::make('fecha')
                ->label('Fecha (Y-m-d)')
                ->rules(['nullable', 'date']),
            ImportColumn::make('hora')
                ->label('Hora (H:i)')
                ->rules(['nullable']),
            ImportColumn::make('fase')
                ->label('Fase (grupo, octavos, cuartos, semifinal, final, amistoso)')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('instalacion_id')
                ->label('ID Instalación')
                ->numeric()
                ->rules(['nullable', 'integer']),
            ImportColumn::make('arbitro_id')
                ->label('ID Árbitro')
                ->numeric()
                ->rules(['nullable', 'integer']),
            ImportColumn::make('resultado')
                ->label('Resultado')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('tenant_id')
                ->label('ID del Tenant (Opcional)')
                ->numeric()
                ->rules(['nullable', 'integer']),
        ];
    }

    public function resolveRecord(): ?Partido
    {
        // Se puede crear uno nuevo siempre, o verificar si el partido ya existe con el local, visitante y fecha
        return Partido::firstOrNew([
            'local_id' => $this->data['local_id'],
            'visitante_id' => $this->data['visitante_id'],
            'fecha' => $this->data['fecha'] ?? null,
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Se importaron ' . number_format($import->successful_rows) . ' ' . str('partido')->plural($import->successful_rows) . '.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' fallaron.';
        }

        return $body;
    }
}
