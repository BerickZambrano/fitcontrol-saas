<?php

namespace App\Filament\Resources\Instalacions;

use App\Filament\Resources\Instalacions\Pages\CreateInstalacion;
use App\Filament\Resources\Instalacions\Pages\EditInstalacion;
use App\Filament\Resources\Instalacions\Pages\ListInstalacions;
use App\Filament\Resources\Instalacions\Schemas\InstalacionForm;
use App\Filament\Resources\Instalacions\Tables\InstalacionsTable;
use App\Filament\Traits\HasTenantGlobalSearch;
use App\Models\Instalacion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class InstalacionResource extends Resource
{
    use HasTenantGlobalSearch;
    protected static ?string $model = Instalacion::class;

    protected static ?string $navigationLabel = 'Instalaciones';
    protected static ?string $modelLabel = 'Instalación';

    protected static string|UnitEnum|null $navigationGroup = 'Administración';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return InstalacionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstalacionsTable::configure($table);
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
            'index' => ListInstalacions::route('/'),
            'create' => CreateInstalacion::route('/create'),
            'edit' => EditInstalacion::route('/{record}/edit'),
        ];
    }
}
