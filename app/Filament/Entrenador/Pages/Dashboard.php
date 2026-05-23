<?php

namespace App\Filament\Entrenador\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Entrenador\Widgets\CoachStatsWidget;
use BackedEnum;

class Dashboard extends BaseDashboard
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Panel del Entrenador';

    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'md' => 12,
        ];
    }

    public function getWidgets(): array
    {
        return [
            CoachStatsWidget::class,
        ];
    }
}
