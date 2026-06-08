<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewTenantRequestNotification;

class ApprovalService
{
    public function notifyAdmin(Tenant $tenant): void
    {
        // Buscar super_admins — rol principal del sistema
        // Se incluye 'Administrador' como fallback por compatibilidad
        $admins = User::role(['super_admin', 'Administrador'])->get();

        if ($admins->isEmpty()) {
            Log::warning("Nueva solicitud de tenant '{$tenant->nombre}' sin admins para notificar. Verifica que existan usuarios con rol super_admin.");
            return;
        }

        Notification::send($admins, new NewTenantRequestNotification($tenant));

        Log::info("Notificación de nuevo tenant '{$tenant->nombre}' enviada a {$admins->count()} administrador(es).");
    }
}
