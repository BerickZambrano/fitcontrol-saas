<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Partido;
use App\Models\Equipo;

class NuevoPartidoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $partido;

    /**
     * Create a new notification instance.
     */
    public function __construct(Partido $partido)
    {
        $this->partido = $partido;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $fecha = $this->partido->fecha ? \Carbon\Carbon::parse($this->partido->fecha)->format('d/m/Y') : 'Sin fecha';
        $hora = $this->partido->hora ? \Carbon\Carbon::parse($this->partido->hora)->format('H:i') : 'Sin hora';

        $equipoLocal = $this->partido->equipo_local_id 
            ? Equipo::find($this->partido->equipo_local_id)?->nombre ?? 'Equipo local' 
            : 'Equipo local';
        $equipoVisitante = $this->partido->equipo_visitante_id 
            ? Equipo::find($this->partido->equipo_visitante_id)?->nombre ?? 'Equipo visitante' 
            : 'Equipo visitante';

        return [
            'partido_id' => $this->partido->id,
            'equipo_local_id' => $this->partido->equipo_local_id,
            'equipo_visitante_id' => $this->partido->equipo_visitante_id,
            'titulo' => 'Nuevo partido: ' . $equipoLocal . ' vs ' . $equipoVisitante,
            'mensaje' => "📅 {$fecha} ⏰ {$hora}",
            'tipo' => 'partido',
        ];
    }
}
