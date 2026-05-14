<?php

namespace App\Filament\Jugador\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

/**
 * Placeholder for QR scanning feature.
 * TODO: Implement camera + html5-qrcode integration.
 */
class EscanearQR extends Page
{
    protected static BackedEnum|string|null $navigationIcon = Heroicon::QrCode;
    protected static ?string $navigationLabel = 'Marcar Asistencia';
    protected static ?string $title = 'Marcar Asistencia';
    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.jugador.pages.escanear-qr';

    public static function canAccess(): bool
    {
        return false; // TODO: Enable when QR feature is ready
    }
}
