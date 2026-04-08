<?php

namespace App\Filament\Resources\Pagos;

use App\Filament\Resources\Pagos\Pages;
use App\Filament\Resources\Pagos\Schemas\PagoForm;
use App\Filament\Resources\Pagos\Tables\PagosTable;
use App\Models\Pago;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;

class PagoResource extends Resource
{
    protected static ?string $model = Pago::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|UnitEnum|null $navigationGroup = 'Finanzas';

    protected static ?string $recordTitleAttribute = 'id_usu_fk';

    public static function form(Schema $schema): Schema
    {
        return PagoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PagosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPagos::route('/'),
            'create' => Pages\CreatePago::route('/create'),
            'edit'   => Pages\EditPago::route('/{record}/edit'),
        ];
    }
}
