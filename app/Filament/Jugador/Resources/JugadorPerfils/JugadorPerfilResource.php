<?php

namespace App\Filament\Jugador\Resources\JugadorPerfils;

use App\Filament\Jugador\Resources\JugadorPerfils\Pages\CreateJugadorPerfil;
use App\Filament\Jugador\Resources\JugadorPerfils\Pages\EditJugadorPerfil;
use App\Filament\Jugador\Resources\JugadorPerfils\Pages\ListJugadorPerfils;
use App\Filament\Jugador\Resources\JugadorPerfils\Schemas\JugadorPerfilForm;
use App\Filament\Jugador\Resources\JugadorPerfils\Tables\JugadorPerfilsTable;
use App\Filament\Traits\HasTenantGlobalSearch;
use App\Models\JugadorPerfil;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JugadorPerfilResource extends Resource
{
    use HasTenantGlobalSearch;
    protected static ?string $model = JugadorPerfil::class;

    protected static ?string $navigationLabel = 'Directorio Jugadores';
    protected static ?string $modelLabel = 'Jugador';
    protected static ?string $pluralModelLabel = 'Directorio de Jugadores';
    protected static ?string $slug = 'directorio-jugadores';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';


    protected static ?string $recordTitleAttribute = 'user_id';

    public static function form(Schema $schema): Schema
    {
        return JugadorPerfilForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JugadorPerfilsTable::configure($table);
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
            'index' => ListJugadorPerfils::route('/'),
            'create' => CreateJugadorPerfil::route('/create'),
            'edit' => EditJugadorPerfil::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['user.name', 'posicion', 'dorsal'];
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string|\Illuminate\Contracts\Support\Htmlable
    {
        return $record->user ? $record->user->name : 'Jugador';
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Posición' => $record->posicion,
            'Dorsal' => $record->dorsal,
        ];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return static::getUrl('edit', ['record' => $record]);
    }
}
