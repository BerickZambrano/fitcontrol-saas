<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Tenant;

class NewTenantRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tenant;

    /**
     * Create a new notification instance.
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nueva solicitud de club: ' . $this->tenant->nombre)
            ->greeting('¡Hola, Administrador!')
            ->line('Se ha recibido una nueva solicitud de acceso para un club deportivo en FitControl.')
            ->line('**Club:** ' . $this->tenant->nombre)
            ->line('**Encargado:** ' . $this->tenant->encargado_nombre)
            ->line('**Plan:** ' . $this->tenant->plan)
            ->action('Revisar solicitud', url('/admin/tenants/' . $this->tenant->id . '/edit'))
            ->line('Gracias por usar FitControl.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tenant_id' => $this->tenant->id,
            'nombre'    => $this->tenant->nombre,
            'mensaje'   => 'Nueva solicitud de acceso recibida.',
        ];
    }
}
