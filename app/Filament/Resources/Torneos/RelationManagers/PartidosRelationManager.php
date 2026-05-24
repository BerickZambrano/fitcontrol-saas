<?php

namespace App\Filament\Resources\Torneos\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Schema;
use App\Models\Equipo;
use App\Models\Instalacion;
use Filament\Forms;

class PartidosRelationManager extends RelationManager
{
    protected static string $relationship = 'partidos';

    protected static ?string $title = 'Fixture de Partidos';

    protected static ?string $modelLabel = 'Partido';
    protected static ?string $pluralModelLabel = 'Partidos';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\DatePicker::make('fecha')
                ->label('Fecha')
                ->required(),

            Forms\Components\TimePicker::make('hora')
                ->label('Hora')
                ->required(),

            Forms\Components\Select::make('equipo_local_id')
                ->label('Equipo Local')
                ->required()
                ->options(function () {
                    $query = Equipo::query()->withoutGlobalScopes();
                    if (!auth()->user()->hasRole('super_admin')) {
                        $query->where('tenant_id', auth()->user()->tenant_id);
                    }
                    return $query->pluck('nombre', 'id');
                })
                ->searchable()
                ->preload(),

            Forms\Components\Select::make('equipo_visitante_id')
                ->label('Equipo Visitante')
                ->required()
                ->options(function () {
                    $query = Equipo::query()->withoutGlobalScopes();
                    if (!auth()->user()->hasRole('super_admin')) {
                        $query->where('tenant_id', auth()->user()->tenant_id);
                    }
                    return $query->pluck('nombre', 'id');
                })
                ->searchable()
                ->preload(),

            Forms\Components\Select::make('fase')
                ->label('Fase del Torneo')
                ->nullable()
                ->placeholder('Sin fase asignada')
                ->options([
                    'grupo'     => 'Fase de Grupos',
                    'octavos'   => 'Octavos de Final',
                    'cuartos'   => 'Cuartos de Final',
                    'semifinal' => 'Semifinal',
                    'final'     => 'Final',
                    'amistoso'  => 'Amistoso',
                ]),

            Forms\Components\TextInput::make('resultado')
                ->label('Resultado')
                ->maxLength(255)
                ->placeholder('Ej: 2-1'),

            Forms\Components\Select::make('instalacion_id')
                ->label('Instalación')
                ->options(fn () => Instalacion::query()->pluck('nombre', 'id'))
                ->searchable()
                ->preload()
                ->nullable()
                ->placeholder('Sin instalación específica'),

            Forms\Components\Hidden::make('tenant_id')
                ->default(fn () => auth()->user()->tenant_id),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['local', 'visitante', 'instalacion'])->orderBy('fase')->orderBy('fecha'))
            ->columns([
                TextColumn::make('fase')
                    ->label('Fase')
                    ->placeholder('—')
                    ->badge()
                    ->color(fn (string|null $state) => match ($state) {
                        'grupo'     => 'gray',
                        'octavos'   => 'info',
                        'cuartos'   => 'warning',
                        'semifinal' => 'primary',
                        'final'     => 'success',
                        'amistoso'  => 'secondary',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string|null $state) => match ($state) {
                        'grupo'     => 'Grupos',
                        'octavos'   => 'Octavos',
                        'cuartos'   => 'Cuartos',
                        'semifinal' => 'Semifinal',
                        'final'     => 'Final',
                        'amistoso'  => 'Amistoso',
                        default     => $state ?? '—',
                    })
                    ->sortable(),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('hora')
                    ->label('Hora')
                    ->sortable(),

                TextColumn::make('local.nombre')
                    ->label('Local')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('resultado')
                    ->label('Resultado')
                    ->placeholder('—')
                    ->alignCenter(),

                TextColumn::make('visitante.nombre')
                    ->label('Visitante')
                    ->searchable(),

                TextColumn::make('instalacion.nombre')
                    ->label('Instalación')
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('fase')
                    ->label('Fase')
                    ->options([
                        'grupo'     => 'Fase de Grupos',
                        'octavos'   => 'Octavos de Final',
                        'cuartos'   => 'Cuartos de Final',
                        'semifinal' => 'Semifinal',
                        'final'     => 'Final',
                        'amistoso'  => 'Amistoso',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()->label('Añadir Partido'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('fase')
            ->emptyStateHeading('Sin partidos registrados')
            ->emptyStateDescription('Añade partidos al fixture de este torneo usando el botón "Añadir Partido".');
    }
}
