<?php

namespace App\Filament\Admin\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use App\Models\Entrenamiento;
use App\Models\Partido;

class Calendario extends Page
{
    protected static BackedEnum|string|null $navigationIcon = Heroicon::Calendar;
    protected static ?string $navigationLabel = 'Calendario';
    protected static ?string $title = 'Calendario';

    protected string $view = 'filament.pages.calendario';

    public function getEvents(): array
    {
        $entrenamientos = Entrenamiento::all()->map(fn ($e) => [
            'title' => 'Entrenamiento',
            'start' => $e->fecha,
            'description' => $e->nombre,
            'color' => '#2563eb',
        ]);

        $partidos = Partido::all()->map(fn ($p) => [
            'title' => 'Partido',
            'start' => $p->fecha,
            'description' => $p->descripcion,
            'color' => '#16a34a',
        ]);

        return $entrenamientos
            ->merge($partidos)
            ->values()
            ->toArray();
    }
}