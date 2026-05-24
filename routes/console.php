<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use App\Models\Pago;
use App\Models\User;
use Filament\Notifications\Notification;

Schedule::call(function () {
    // Buscar pagos pendientes con fecha de vencimiento anterior al día de hoy
    $pagosVencidos = Pago::where('estado', 'pendiente')
        ->where('fecha', '<', today())
        ->get();

    foreach ($pagosVencidos as $pago) {
        $jugador = $pago->usuario;
        if (!$jugador) {
            continue;
        }

        // Obtener administradores del mismo tenant/club
        $admins = User::role('Administrador')
            ->where('tenant_id', $pago->tenant_id)
            ->get();

        if ($admins->isNotEmpty()) {
            Notification::make()
                ->title('Alerta: Pago Vencido ⚠️')
                ->body("El jugador " . $jugador->name . " tiene un pago pendiente de $" . number_format($pago->monto, 2) . " que venció el " . \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') . ".")
                ->warning()
                ->icon('heroicon-o-credit-card')
                ->sendToDatabase($admins);
        }
    }
})->daily();
