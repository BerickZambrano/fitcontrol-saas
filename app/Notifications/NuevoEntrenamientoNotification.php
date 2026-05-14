<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Entrenamiento;

class NuevoEntrenamientoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $entrenamiento;

    /**
     * Create a new notification instance.
     */
    public function __construct(Entrenamiento $entrenamiento)
    {
        $this->entrenamiento = $entrenamiento;
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
        $fecha = $this->entrenamiento->fecha ? \Carbon\Carbon::parse($this->entrenamiento->fecha)->format('d/m/Y') : 'Sin fecha';
        $hora = $this->entrenamiento->hora ? \Carbon\Carbon::parse($this->entrenamiento->hora)->format('H:i') : 'Sin hora';
        $ubicacion = $this->entrenamiento->ubicacion ?? 'Sin ubicación';

        return [
            'entrenamiento_id' => $this->entrenamiento->id,
            'equipo_id' => $this->entrenamiento->equipo_id,
            'titulo' => 'Nuevo entrenamiento: ' . $this->entrenamiento->nombre,
            'mensaje' => "📅 {$fecha} ⏰ {$hora} 📍 {$ubicacion}",
            'tipo' => 'entrenamiento',
        ];
    }
}
