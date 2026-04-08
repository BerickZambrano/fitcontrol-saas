<?php

namespace App\Filament\Resources\Torneos;

use App\Filament\Resources\Torneos\Pages\CreateTorneo;
use App\Filament\Resources\Torneos\Pages\EditTorneo;
use App\Filament\Resources\Torneos\Pages\ListTorneos;
use App\Filament\Resources\Torneos\Schemas\TorneoForm;
use App\Filament\Resources\Torneos\Tables\TorneosTable;
use App\Models\Torneo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TorneoResource extends Resource
{
    protected static ?string $model = Torneo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'torneo_id';

    protected static string|UnitEnum|null $navigationGroup = 'Competencias';

    public static function form(Schema $schema): Schema
    {
        return TorneoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TorneosTable::configure($table);
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
            'index' => ListTorneos::route('/'),
            'create' => CreateTorneo::route('/create'),
            'edit' => EditTorneo::route('/{record}/edit'),
        ];
    }
}
