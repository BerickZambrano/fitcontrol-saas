<?php

namespace App\Filament\Jugador\Resources\JugadorPerfils;

use App\Filament\Jugador\Resources\JugadorPerfils\Pages\CreateJugadorPerfil;
use App\Filament\Jugador\Resources\JugadorPerfils\Pages\EditJugadorPerfil;
use App\Filament\Jugador\Resources\JugadorPerfils\Pages\ListJugadorPerfils;
use App\Filament\Jugador\Resources\JugadorPerfils\Schemas\JugadorPerfilForm;
use App\Filament\Jugador\Resources\JugadorPerfils\Tables\JugadorPerfilsTable;
use App\Models\JugadorPerfil;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JugadorPerfilResource extends Resource
{
    protected static ?string $model = JugadorPerfil::class;
    
    protected static ?string $navigationLabel = 'Perfiles de Jugadores';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static string|UnitEnum|null $navigationGroup = 'Jugadores';

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
}
