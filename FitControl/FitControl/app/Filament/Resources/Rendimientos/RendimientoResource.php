<?php

namespace App\Filament\Resources\Rendimientos;

use App\Filament\Resources\Rendimientos\Pages\CreateRendimiento;
use App\Filament\Resources\Rendimientos\Pages\EditRendimiento;
use App\Filament\Resources\Rendimientos\Pages\ListRendimientos;
use App\Filament\Resources\Rendimientos\Schemas\RendimientoForm;
use App\Filament\Resources\Rendimientos\Tables\RendimientosTable;
use App\Filament\Traits\HasTenantGlobalSearch;
use App\Models\Rendimiento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RendimientoResource extends Resource
{
    use HasTenantGlobalSearch;
    protected static ?string $model = Rendimiento::class;

    protected static string|UnitEnum|null $navigationGroup = 'Jugadores';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'user_id';

    public static function form(Schema $schema): Schema
    {
        return RendimientoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RendimientosTable::configure($table);
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
            'index' => ListRendimientos::route('/'),
            'create' => CreateRendimiento::route('/create'),
            'edit' => EditRendimiento::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['user.name', 'goles', 'asistencias'];
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string|\Illuminate\Contracts\Support\Htmlable
    {
        return $record->user ? $record->user->name : 'Rendimiento';
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Goles' => $record->goles,
            'Asistencias' => $record->asistencias,
        ];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return static::getUrl('edit', ['record' => $record]);
    }
}
