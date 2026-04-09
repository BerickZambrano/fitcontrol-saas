<?php

namespace App\Filament\Resources\Tenants;

use App\Filament\Resources\Tenants\Pages\CreateTenant;
use App\Filament\Resources\Tenants\Pages\EditTenant;
use App\Filament\Resources\Tenants\Pages\ListTenants;
use App\Filament\Resources\Tenants\Schemas\TenantForm;
use App\Filament\Resources\Tenants\Tables\TenantsTable;
use App\Filament\Traits\HasTenantGlobalSearch;
use App\Models\Tenant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TenantResource extends Resource
{
    use HasTenantGlobalSearch;
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationLabel = 'Clubes';
    protected static ?string $modelLabel = 'Club';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;
    protected static ?string $recordTitleAttribute = 'nombre';
    protected static string|UnitEnum|null $navigationGroup = 'Administración';

    public static function form(Schema $schema): Schema
    


    {
        return TenantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenantsTable::configure($table);
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
            'index' => ListTenants::route('/'),
            'create' => CreateTenant::route('/create'),
            'edit' => EditTenant::route('/{record}/edit'),
        ];
    }
}
