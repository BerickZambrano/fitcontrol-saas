<?php

namespace App\Filament\Resources\HistorialMedicos;

use App\Filament\Resources\HistorialMedicos\Pages\CreateHistorialMedico;
use App\Filament\Resources\HistorialMedicos\Pages\EditHistorialMedico;
use App\Filament\Resources\HistorialMedicos\Pages\ListHistorialMedicos;
use App\Filament\Resources\HistorialMedicos\Schemas\HistorialMedicoForm;
use App\Filament\Resources\HistorialMedicos\Tables\HistorialMedicosTable;
use App\Models\HistorialMedico;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HistorialMedicoResource extends Resource
{
    protected static ?string $model = HistorialMedico::class;
        
    protected static string|UnitEnum|null $navigationGroup = 'Jugadores';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'tipo';

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
}
