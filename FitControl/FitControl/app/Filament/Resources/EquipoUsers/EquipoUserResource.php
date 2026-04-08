<?php

namespace App\Filament\Resources\EquipoUsers;

use App\Filament\Resources\EquipoUsers\Pages\CreateEquipoUser;
use App\Filament\Resources\EquipoUsers\Pages\EditEquipoUser;
use App\Filament\Resources\EquipoUsers\Pages\ListEquipoUsers;
use App\Filament\Resources\EquipoUsers\Schemas\EquipoUserForm;
use App\Filament\Resources\EquipoUsers\Tables\EquipoUsersTable;
use App\Models\Equipo;
use App\Models\EquipoUser;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EquipoUserResource extends Resource
{
    protected static ?string $model = EquipoUser::class;

      protected static ?string $navigationLabel = 'Equipo->Usuarios';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Equipos';

    protected static ?string $recordTitleAttribute = 'equipo_id';

    public static function form(Schema $schema): Schema
    {
        return EquipoUserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EquipoUsersTable::configure($table);
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
            'index' => ListEquipoUsers::route('/'),
            'create' => CreateEquipoUser::route('/create'),
            'edit' => EditEquipoUser::route('/{record}/edit'),
        ];
    }
}
