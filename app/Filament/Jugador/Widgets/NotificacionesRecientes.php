<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\Notificacion;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class NotificacionesRecientes extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 6;

    protected function getStats(): array
    {
        $user = auth()->user();

        $total = Notificacion::where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->count();

        $noLeidas = Notificacion::where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->where('leida', false)
            ->count();

        $ultima = Notificacion::where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->latest('created_at')
            ->first();

        return [
            Stat::make('Total Notificaciones', $total)
                ->description($noLeidas . ' sin leer')
                ->icon('heroicon-o-bell')
                ->color($noLeidas > 0 ? 'warning' : 'success'),

            Stat::make('Última Notificación', $ultima?->titulo ?? 'Sin notificaciones')
                ->description($ultima?->created_at?->diffForHumans() ?? 'N/A')
                ->icon('heroicon-o-envelope')
                ->color('info'),
        ];
    }
}
