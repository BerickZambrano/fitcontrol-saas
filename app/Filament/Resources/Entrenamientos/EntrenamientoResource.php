<?php

namespace App\Filament\Resources\Entrenamientos;

use App\Filament\Resources\Entrenamientos\Pages\CreateEntrenamiento;
use App\Filament\Resources\Entrenamientos\Pages\EditEntrenamiento;
use App\Filament\Resources\Entrenamientos\Pages\ListEntrenamientos;
use App\Filament\Resources\Entrenamientos\Schemas\EntrenamientoForm;
use App\Filament\Resources\Entrenamientos\Tables\EntrenamientosTable;
use App\Models\Entrenamiento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EntrenamientoResource extends Resource
{
    protected static ?string $model = Entrenamiento::class;

    protected static string|UnitEnum|null $navigationGroup = 'Entrenamientos';


    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'nombre';

    // FORMULARIO
    public static function form(Schema $schema): Schema
    {
        return EntrenamientoForm::configure($schema);
    }

    // TABLA
    public static function table(Table $table): Table
    {
        return EntrenamientosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEntrenamientos::route('/'),
            'create' => CreateEntrenamiento::route('/create'),
            'edit' => EditEntrenamiento::route('/{record}/edit'),
        ];
    }
}