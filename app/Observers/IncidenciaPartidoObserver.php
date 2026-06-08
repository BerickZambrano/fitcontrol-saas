<?php

namespace App\Observers;

use App\Models\IncidenciaPartido;
use App\Models\Sancion;

class IncidenciaPartidoObserver
{
    /**
     * Handle the IncidenciaPartido "created" event.
     */
    public function created(IncidenciaPartido $incidencia): void
    {
        // Si la incidencia es una tarjeta roja, se genera una sanción automática (ej: 1 partido de suspensión)
        if ($incidencia->tipo === 'roja') {
            Sancion::create([
                'jugador_id' => $incidencia->jugador_id,
                'partido_id_origen' => $incidencia->partido_id,
                'cantidad_partidos_suspension' => 1,
                'estado' => 'activa',
            ]);
        }
    }
}
