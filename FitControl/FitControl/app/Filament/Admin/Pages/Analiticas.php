<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class Analiticas extends Page
{
    protected static BackedEnum|string|null $navigationIcon = Heroicon::ChartBar;
    protected static ?string $navigationLabel = 'Analíticas';
    protected static string|UnitEnum|null $navigationGroup = 'Reportes';
    protected static ?string $title = 'Analíticas del Club';
    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.analiticas';

    public static function canAccess(): bool
    {
        return auth()->user()->can('View: Dashboard');
    }

    /**
     * Get the widgets that should be displayed on this page.
     * These are chart widgets that were moved here from the dashboard.
     */
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\AsistenciaPorMes::class,
            \App\Filament\Widgets\EntrenamientosPorMes::class,
            \App\Filament\Widgets\TotalUsuarios::class,
            \App\Filament\Widgets\PagosPorMes::class,
        ];
    }

    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'lg' => 2,
        ];
    }
}
