<?php

namespace App\Events;

use App\Models\Partido;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PartidoCercano
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Partido $partido)
    {
    }
}
