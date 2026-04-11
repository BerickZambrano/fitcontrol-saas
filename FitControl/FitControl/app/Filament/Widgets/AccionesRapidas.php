<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Entrenamientos\EntrenamientoResource;
use App\Filament\Resources\Partidos\PartidoResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Admin\Pages\Reportes\GenerarReporte;
use Filament\Widgets\Widget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class AccionesRapidas extends Widget
{
    use HasWidgetShield;

    protected string $view = 'filament.widgets.acciones-rapidas';

    protected static ?string $heading = null;

    protected static ?int $sort = 0;

    public function getActions(): array
    {
        return [
            [
                'label' => 'Programar entrenamiento',
                'icon' => 'heroicon-o-calendar-days',
                'color' => 'primary',
                'url' => EntrenamientoResource::getUrl('create'),
            ],
            [
                'label' => 'Crear partido',
                'icon' => 'heroicon-o-trophy',
                'color' => 'success',
                'url' => PartidoResource::getUrl('create'),
            ],
            [
                'label' => 'Agregar jugador',
                'icon' => 'heroicon-o-user-plus',
                'color' => 'info',
                'url' => UserResource::getUrl('create'),
            ],
            [
                'label' => 'Generar reporte',
                'icon' => 'heroicon-o-document-chart-bar',
                'color' => 'warning',
                'url' => GenerarReporte::getUrl(),
            ],
        ];
    }
}
