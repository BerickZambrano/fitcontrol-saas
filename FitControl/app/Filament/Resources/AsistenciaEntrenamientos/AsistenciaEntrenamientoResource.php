<?php

namespace App\Filament\Resources\AsistenciaEntrenamientos;

use App\Filament\Resources\AsistenciaEntrenamientos\Pages\CreateAsistenciaEntrenamiento;
use App\Filament\Resources\AsistenciaEntrenamientos\Pages\EditAsistenciaEntrenamiento;
use App\Filament\Resources\AsistenciaEntrenamientos\Pages\ListAsistenciaEntrenamientos;
use App\Filament\Resources\AsistenciaEntrenamientos\Schemas\AsistenciaEntrenamientoForm;
use App\Filament\Resources\AsistenciaEntrenamientos\Tables\AsistenciaEntrenamientosTable;
use App\Models\AsistenciaEntrenamiento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AsistenciaEntrenamientoResource extends Resource
{
    protected static ?string $model = AsistenciaEntrenamiento::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckCircle;

    protected static string|UnitEnum|null $navigationGroup = 'Entrenamientos';
   
    protected static ?string $recordTitleAttribute = 'entrenamiento_id';

    public static function form(Schema $schema): Schema
    {
        return AsistenciaEntrenamientoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AsistenciaEntrenamientosTable::configure($table);
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
            'index' => ListAsistenciaEntrenamientos::route('/'),
            'create' => CreateAsistenciaEntrenamiento::route('/create'),
            'edit' => EditAsistenciaEntrenamiento::route('/{record}/edit'),
        ];
    }
}
