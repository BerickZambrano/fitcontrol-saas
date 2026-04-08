<?php

namespace App\Filament\Resources\Partidos;

use App\Filament\Resources\Partidos\Pages\CreatePartido;
use App\Filament\Resources\Partidos\Pages\EditPartido;
use App\Filament\Resources\Partidos\Pages\ListPartidos;
use App\Filament\Resources\Partidos\Schemas\PartidoForm;
use App\Filament\Resources\Partidos\Tables\PartidosTable;
use App\Models\Partido;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartidoResource extends Resource
{
    protected static ?string $model = Partido::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static string|UnitEnum|null $navigationGroup = 'Competencias';

    protected static ?string $recordTitleAttribute = 'fecha';

    public static function form(Schema $schema): Schema
    {
        return PartidoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartidosTable::configure($table);
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
            'index' => ListPartidos::route('/'),
            'create' => CreatePartido::route('/create'),
            'edit' => EditPartido::route('/{record}/edit'),
        ];
    }
}
