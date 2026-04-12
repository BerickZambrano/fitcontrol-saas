<?php

namespace App\Filament\Jugador\Widgets;

use App\Models\Notificacion;
use Filament\Widgets\Widget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class MisNotificaciones extends Widget
{
    use HasWidgetShield;

    protected string $view = 'filament.jugador.widgets.mis-notificaciones';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function getNotificaciones(): array
    {
        $user = auth()->user();

        return Notificacion::where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }
}
