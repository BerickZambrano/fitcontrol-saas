<?php

namespace App\Events;

use App\Models\Entrenamiento;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EntrenamientoCreado
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Entrenamiento $entrenamiento)
    {
    }
}
