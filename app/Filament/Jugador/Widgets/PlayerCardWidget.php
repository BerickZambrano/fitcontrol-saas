<?php

namespace App\Filament\Jugador\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class PlayerCardWidget extends Widget
{
    protected string $view = 'filament.jugador.widgets.player-card-widget';

    protected int | string | array $columnSpan = [
        'md' => 2,
        'lg' => 2,
    ];

    public function getPlayerData()
    {
        $user = Auth::user();
        return $user->jugadorPerfil;
    }

    public function getUser()
    {
        return Auth::user();
    }

    public function getPanelId(): string
    {
        return \Filament\Facades\Filament::getCurrentPanel()->getId();
    }
}
