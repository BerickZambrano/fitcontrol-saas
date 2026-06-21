<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class EntrenadorResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Entrenadores';
    protected static ?string $modelLabel = 'Entrenador';
    protected static ?string $pluralModelLabel = 'Entrenadores';
    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|\UnitEnum|null $navigationGroup = 'Administración';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->role('Entrenador')
            ->when(!auth()->user()->hasRole('super_admin'), function ($query) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            });
    }

    public static function form(Schema $schema): Schema
    {
        $isSuperAdmin = auth()->check() && auth()->user()->hasRole('super_admin');

        return $schema->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label('Correo Electrónico')
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'unique' => 'Este correo electrónico ya está registrado en el sistema.',
                ]),

            Forms\Components\TextInput::make('password')
                ->label('Contraseña')
                ->password()
                ->revealable()
                ->confirmed()
                ->minLength(8)
                ->maxLength(255)
                ->required(fn (string $operation) => $operation === 'create')
                ->afterStateHydrated(fn ($component) => $component->state(null))
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn ($state) => filled($state)),

            Forms\Components\TextInput::make('password_confirmation')
                ->label('Confirmar contraseña')
                ->password()
                ->revealable()
                ->dehydrated(false),

            Forms\Components\Select::make('tenant_id')
                ->label('Tenant')
                ->relationship('tenant', 'nombre')
                ->visible(fn () => $isSuperAdmin)
                ->required($isSuperAdmin)
                ->default(fn () => $isSuperAdmin ? null : auth()->user()->tenant_id)
                ->disabled(fn () => !$isSuperAdmin),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\EntrenadorResource\Pages\ListEntrenadores::route('/'),
            'create' => \App\Filament\Resources\EntrenadorResource\Pages\CreateEntrenador::route('/create'),
            'edit' => \App\Filament\Resources\EntrenadorResource\Pages\EditEntrenador::route('/{record}/edit'),
        ];
    }
}
