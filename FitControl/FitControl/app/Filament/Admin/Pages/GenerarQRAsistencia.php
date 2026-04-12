<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

/**
 * Placeholder for QR generation feature.
 * TODO: Implement dynamic QR generation with expiration.
 */
class GenerarQRAsistencia extends Page
{
    protected static BackedEnum|string|null $navigationIcon = Heroicon::QrCode;
    protected static ?string $navigationLabel = 'Generar QR Asistencia';
    protected static string|UnitEnum|null $navigationGroup = 'Entrenamientos';
    protected static ?string $title = 'Generar QR de Asistencia';
    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.generar-qr-asistencia';

    public static function canAccess(): bool
    {
        return false; // TODO: Enable when QR feature is ready
    }
}
