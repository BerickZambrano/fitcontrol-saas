<?php

namespace App\Filament\Resources\Partidos\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ConvocatoriasRelationManager extends RelationManager
{
    protected static string $relationship = 'convocatorias';
    protected static ?string $recordTitleAttribute = 'estado_asistencia';
    protected static ?string $title = 'Jugadores Convocados';

    public function form($schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('jugador_id')
                    ->label('Jugador')
                    ->options(function ($livewire) {
                        $entrenador = auth()->user();
                        $jugadoresConSancion = \App\Models\Sancion::where('estado', 'activa')->pluck('jugador_id')->toArray();
                        $jugadoresLesionados = \App\Models\HistorialMedico::where('apto', false)
                            ->where(function($q) {
                                $q->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', now()->toDateString());
                            })->pluck('user_id')->toArray();

                        $usuarios = User::role('Jugador')
                            ->where('tenant_id', $entrenador->tenant_id)
                            ->get();

                        $options = [];
                        foreach($usuarios as $user) {
                            if (in_array($user->id, $jugadoresConSancion)) {
                                $options[$user->id] = "<span style='color: red; text-decoration: line-through;'>{$user->name} (Sancionado)</span>";
                            } elseif (in_array($user->id, $jugadoresLesionados)) {
                                $options[$user->id] = "<span style='color: red; font-weight: bold;'>{$user->name} (Lesionado)</span>";
                            } else {
                                $options[$user->id] = $user->name;
                            }
                        }
                        
                        return $options;
                    })
                    ->allowHtml()
                    ->disableOptionWhen(function (string $value) {
                        static $noElegibles = null;
                        if ($noElegibles === null) {
                            $sancionados = \App\Models\Sancion::where('estado', 'activa')->pluck('jugador_id')->toArray();
                            $lesionados = \App\Models\HistorialMedico::where('apto', false)
                                ->where(function($q) {
                                    $q->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', now()->toDateString());
                                })->pluck('user_id')->toArray();
                            $noElegibles = array_merge($sancionados, $lesionados);
                        }
                        return in_array($value, $noElegibles);
                    })
                    ->searchable()
                    ->required()
                    ->unique(
                        table: 'convocatorias',
                        column: 'jugador_id',
                        modifyRuleUsing: fn (\Illuminate\Validation\Rules\Unique $rule, $livewire) => $rule->where('partido_id', $livewire->getOwnerRecord()->id),
                        ignoreRecord: true
                    )
                    ->validationMessages([
                        'unique' => 'Este jugador ya está convocado para este partido.',
                    ]),
                Forms\Components\Hidden::make('equipo_id')
                    ->default(fn ($livewire) => $livewire->getOwnerRecord()->equipo_local_id),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('estado_asistencia')
            ->columns([
                Tables\Columns\TextColumn::make('jugador.name')->label('Jugador'),
                Tables\Columns\TextColumn::make('equipo.nombre')->label('Equipo'),
                Tables\Columns\TextColumn::make('estado_asistencia')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'convocado' => 'warning',
                        'confirmado' => 'success',
                        'rechazado' => 'danger',
                        'asistio' => 'info',
                        'falto' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Convocar Jugador')
                    ->after(function (\App\Models\Convocatoria $record) {
                        $partido = $record->partido;
                        if (!$partido) return;

                        $fecha = $partido->fecha ? \Carbon\Carbon::parse($partido->fecha)->format('d/m/Y') : 'Sin fecha';
                        $hora  = $partido->hora  ? \Carbon\Carbon::parse($partido->hora)->format('H:i')   : 'Sin hora';

                        $equipoLocalNombre    = $partido->equipo_local_id
                            ? \App\Models\Equipo::find($partido->equipo_local_id)?->nombre ?? 'Equipo local'
                            : 'Equipo local';
                        $equipoVisitanteNombre = $partido->equipo_visitante_id
                            ? \App\Models\Equipo::find($partido->equipo_visitante_id)?->nombre ?? 'Equipo visitante'
                            : 'Equipo visitante';

                        $title = 'Partido: ' . $equipoLocalNombre . ' vs ' . $equipoVisitanteNombre;

                        $jugador = \App\Models\User::find($record->jugador_id);
                        if ($jugador) {
                            \Filament\Notifications\Notification::make()
                                ->title($title)
                                ->body("📅 {$fecha} ⏰ {$hora}")
                                ->icon('heroicon-o-trophy')
                                ->color('primary')
                                ->sendToDatabase($jugador);
                        }
                    }),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
