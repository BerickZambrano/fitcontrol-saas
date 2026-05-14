<?php

namespace App\Filament\Resources\JugadorPerfils;

use App\Filament\Resources\JugadorPerfils\Pages\CreateJugadorPerfil;
use App\Filament\Resources\JugadorPerfils\Pages\EditJugadorPerfil;
use App\Filament\Resources\JugadorPerfils\Pages\ListJugadorPerfils;
use App\Filament\Resources\JugadorPerfils\Schemas\JugadorPerfilForm;
use App\Filament\Resources\JugadorPerfils\Tables\JugadorPerfilsTable;
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

    protected static ?string $modelLabel = 'Jugador';
    protected static ?string $pluralModelLabel = 'Jugadores';
    protected static ?string $navigationLabel = 'Jugadores';
    protected static string|UnitEnum|null $navigationGroup = 'Gestión Deportiva';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
