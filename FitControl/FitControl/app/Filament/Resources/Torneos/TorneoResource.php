<?php

namespace App\Filament\Resources\Torneos;

use App\Filament\Resources\Torneos\Pages\CreateTorneo;
use App\Filament\Resources\Torneos\Pages\EditTorneo;
use App\Filament\Resources\Torneos\Pages\ListTorneos;
use App\Filament\Resources\Torneos\Schemas\TorneoForm;
use App\Filament\Resources\Torneos\Tables\TorneosTable;
use App\Filament\Traits\HasTenantGlobalSearch;
use App\Models\Torneo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TorneoResource extends Resource
{
    use HasTenantGlobalSearch;
    protected static ?string $model = Torneo::class;

    protected static ?string $navigationLabel = 'Torneos';
    protected static ?string $modelLabel = 'Torneo';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;

    protected static ?string $recordTitleAttribute = 'nombre';

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

    public static function getGloballySearchableAttributes(): array
    {
        return ['nombre', 'categoria', 'estado'];
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string|\Illuminate\Contracts\Support\Htmlable
    {
        return $record->nombre;
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Categoría' => $record->categoria,
            'Estado' => $record->estado,
        ];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return static::getUrl('edit', ['record' => $record]);
    }
}
