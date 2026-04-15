<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\Notificacion;
use Filament\Widgets\Widget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Illuminate\Support\Facades\DB;

class MisNotificaciones extends Widget
{
    use HasWidgetShield;

    protected string $view = 'filament.jugador.widgets.mis-notificaciones';

    protected static ?int $sort = 11;

    protected int|string|array $columnSpan = 'full';

    public function getNotificaciones(): array
    {
        $user = auth()->user();
        if (!$user) {
            return [];
        }

        $notifications = [];

        // Obtener notificaciones de la tabla notifications (Filament/campana)
        $dbNotifications = DB::table('notifications')
            ->where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($dbNotifications as $notif) {
            $data = json_decode($notif->data, true) ?? [];
            $notifications[] = [
                'titulo' => $data['titulo'] ?? 'Notificación',
                'mensaje' => $data['mensaje'] ?? '',
                'leida' => $notif->read_at !== null,
                'created_at' => $notif->created_at,
                'tipo' => $data['tipo'] ?? 'general',
            ];
        }

        // También incluir notificaciones del sistema custom (notificaciones table)
        $customNotifications = Notificacion::where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($customNotifications as $notif) {
            $notifications[] = [
                'titulo' => $notif->titulo ?? 'Notificación',
                'mensaje' => $notif->mensaje ?? '',
                'leida' => (bool) $notif->leida,
                'created_at' => $notif->created_at,
                'tipo' => 'custom',
            ];
        }

        // Ordenar todas las notificaciones por fecha (más recientes primero)
        usort($notifications, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // Retornar solo las 5 más recientes
        return array_slice($notifications, 0, 5);
    }
}
