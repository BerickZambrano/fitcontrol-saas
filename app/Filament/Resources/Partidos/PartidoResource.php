<?php

namespace App\Filament\Resources\Partidos;

use App\Filament\Resources\Partidos\Pages\CreatePartido;
use App\Filament\Resources\Partidos\Pages\EditPartido;
use App\Filament\Resources\Partidos\Pages\ListPartidos;
use App\Filament\Resources\Partidos\Schemas\PartidoForm;
use App\Filament\Resources\Partidos\Tables\PartidosTable;
use App\Filament\Traits\HasTenantGlobalSearch;
use App\Models\Partido;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartidoResource extends Resource
{
    use HasTenantGlobalSearch;
    protected static ?string $model = Partido::class;

    protected static ?string $navigationLabel = 'Partidos';
    protected static ?string $modelLabel = 'Partido';
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
            RelationManagers\ConvocatoriasRelationManager::class,
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

    public static function getGloballySearchableAttributes(): array
    {
        return ['local.nombre', 'visitante.nombre', 'torneo.nombre'];
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string|\Illuminate\Contracts\Support\Htmlable
    {
        $local = $record->local ? $record->local->nombre : '???';
        $visitante = $record->visitante ? $record->visitante->nombre : '???';
        return $local . ' vs ' . $visitante;
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Torneo' => $record->torneo ? $record->torneo->nombre : '',
            'Fecha' => $record->fecha,
        ];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return static::getUrl('edit', ['record' => $record]);
    }
}
