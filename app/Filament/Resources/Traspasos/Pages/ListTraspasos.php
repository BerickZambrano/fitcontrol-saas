<?php

namespace App\Filament\Resources\Traspasos\Pages;

use App\Filament\Resources\Traspasos\TraspasoResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTraspasos extends ListRecords
{
    protected static string $resource = TraspasoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Solicitar Traspaso')
                ->modalHeading('Solicitar Traspaso de Jugador')
                ->visible(fn () => auth()->user()->hasRole('Administrador') || auth()->user()->equipoUser !== null)
                ->mutateFormDataUsing(function (array $data): array {
                    // Set equipo_origen from the selected player's current team
                    $jugador = User::find($data['jugador_id']);
                    $data['equipo_origen_id'] = $jugador?->equipoUser?->equipo_id;

                    // Set equipo_destino from the logged-in coach's team
                    $data['equipo_destino_id'] = auth()->user()->equipoUser?->equipo_id;

                    // Default estado
                    $data['estado'] = 'pendiente';

                    return $data;
                }),
        ];
    }
}
