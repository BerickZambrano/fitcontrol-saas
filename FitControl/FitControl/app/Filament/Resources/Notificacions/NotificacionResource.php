<?php

namespace App\Filament\Resources\Notificacions;

use App\Filament\Resources\Notificacions\Pages\CreateNotificacion;
use App\Filament\Resources\Notificacions\Pages\EditNotificacion;
use App\Filament\Resources\Notificacions\Pages\ListNotificacions;
use App\Filament\Resources\Notificacions\Schemas\NotificacionForm;
use App\Filament\Resources\Notificacions\Tables\NotificacionsTable;
use App\Models\Notificacion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class NotificacionResource extends Resource
{
    protected static ?string $model = Notificacion::class;
    
    protected static string|UnitEnum|null $navigationGroup = 'Administración';


    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return NotificacionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotificacionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNotificacions::route('/'),
            'create' => CreateNotificacion::route('/create'),
            'edit' => EditNotificacion::route('/{record}/edit'),
        ];
    }
}
