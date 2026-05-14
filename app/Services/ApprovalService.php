<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewTenantRequestNotification;

class ApprovalService
{
    public function notifyAdmin(Tenant $tenant)
    {
        // Obtener administradores del sistema (roles con permiso de gestionar tenants)
        $admins = User::role('admin')->get();

        if ($admins->isEmpty()) {
            // Fallback: primer usuario o log
            \Log::info("Nueva solicitud de tenant: {$tenant->nombre}. No se encontraron admins para notificar.");
            return;
        }

        // Enviar notificación (asegúrate de tener la clase NewTenantRequestNotification creada)
        Notification::send($admins, new NewTenantRequestNotification($tenant));
    }
}
