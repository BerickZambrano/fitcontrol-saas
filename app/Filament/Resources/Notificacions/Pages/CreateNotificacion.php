<?php

namespace App\Filament\Resources\Notificacions\Pages;

use App\Filament\Resources\Notificacions\NotificacionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNotificacion extends CreateRecord
{
    protected static string $resource = NotificacionResource::class;

    protected static ?string $title = 'Crear Notificación';
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $tipoDestinatario = $data['tipo_destinatario'] ?? 'individual';
        $titulo = $data['titulo'];
        $mensaje = $data['mensaje'];
        $tenantId = auth()->user()->tenant_id;

        $targetUsers = collect();

        if ($tipoDestinatario === 'individual') {
            $user = \App\Models\User::find($data['user_id']);
            if ($user) {
                $targetUsers->push($user);
            }
        } elseif ($tipoDestinatario === 'equipo') {
            $equipo = \App\Models\Equipo::find($data['equipo_id']);
            if ($equipo) {
                $targetUsers = $equipo->jugadores;
            }
        } elseif ($tipoDestinatario === 'todos') {
            $targetUsers = \App\Models\User::role('Jugador')
                ->where('tenant_id', $tenantId)
                ->get();
        }

        $firstRecord = null;

        foreach ($targetUsers as $user) {
            if (!$user) {
                continue;
            }

            // 1. Guardar en la tabla histórica custom
            $notif = \App\Models\Notificacion::create([
                'tenant_id' => $tenantId,
                'user_id' => $user->id,
                'titulo' => $titulo,
                'mensaje' => $mensaje,
                'leida' => false,
            ]);

            if (!$firstRecord) {
                $firstRecord = $notif;
            }

            // 2. Enviar a la campana nativa de Filament
            \Filament\Notifications\Notification::make()
                ->title($titulo)
                ->body($mensaje)
                ->icon('heroicon-o-bell')
                ->sendToDatabase($user);
        }

        // Retornar fallback si la lista está vacía
        return $firstRecord ?: \App\Models\Notificacion::create([
            'tenant_id' => $tenantId,
            'user_id' => auth()->id(),
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'leida' => true,
        ]);
    }
}
