<?php

namespace App\Filament\Resources\HistorialMedicos;

use App\Filament\Resources\HistorialMedicos\Pages\CreateHistorialMedico;
use App\Filament\Resources\HistorialMedicos\Pages\EditHistorialMedico;
use App\Filament\Resources\HistorialMedicos\Pages\ListHistorialMedicos;
use App\Filament\Resources\HistorialMedicos\Schemas\HistorialMedicoForm;
use App\Filament\Resources\HistorialMedicos\Tables\HistorialMedicosTable;
use App\Filament\Traits\HasTenantGlobalSearch;
use App\Models\HistorialMedico;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HistorialMedicoResource extends Resource
{
    use HasTenantGlobalSearch;
    protected static ?string $model = HistorialMedico::class;

    protected static ?string $navigationLabel = 'Historial Médico';
    protected static ?string $modelLabel = 'Historial Médico';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;
    protected static string|UnitEnum|null $navigationGroup = 'Jugadores';

    protected static ?string $recordTitleAttribute = 'tipo_lesion';

    public static function form(Schema $schema): Schema
    {
        return HistorialMedicoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HistorialMedicosTable::configure($table);
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
            'index' => ListHistorialMedicos::route('/'),
            'create' => CreateHistorialMedico::route('/create'),
            'edit' => EditHistorialMedico::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['usuario.name', 'tipo_lesion', 'descripcion', 'gravedad'];
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string|\Illuminate\Contracts\Support\Htmlable
    {
        return $record->usuario ? $record->usuario->name : 'Historial Médico';
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Tipo' => $record->tipo_lesion,
            'Gravedad' => $record->gravedad,
        ];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return static::getUrl('edit', ['record' => $record]);
    }
}
