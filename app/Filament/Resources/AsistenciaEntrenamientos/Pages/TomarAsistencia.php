<?php

namespace App\Filament\Resources\AsistenciaEntrenamientos\Pages;

use App\Filament\Resources\AsistenciaEntrenamientos\AsistenciaEntrenamientoResource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Models\EquipoUser;
use App\Models\Equipo;
use App\Models\Entrenamiento;
use App\Models\AsistenciaEntrenamiento;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class TomarAsistencia extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = AsistenciaEntrenamientoResource::class;

    protected static ?string $title = 'Tomar Asistencia en Bloque';

    protected string $view = 'filament.entrenador.pages.tomar-asistencia';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('equipo_id')
                    ->label('Equipo')
                    ->options(function () {
                        // Get active teams where user is trainer/member
                        $teamsIds = EquipoUser::where('user_id', auth()->id())
                            ->where(function ($query) {
                                $query->whereNull('fecha_fin')
                                      ->orWhere('fecha_fin', '>=', now()->toDateString());
                            })
                            ->pluck('equipo_id');
                            
                        return Equipo::whereIn('id', $teamsIds)->pluck('nombre', 'id');
                    })
                    ->live()
                    ->required()
                    ->afterStateUpdated(function (Set $set) {
                        $set('entrenamiento_id', null);
                        $set('asistencias', []);
                    }),

                Select::make('entrenamiento_id')
                    ->label('Entrenamiento')
                    ->options(function (Get $get) {
                        $equipoId = $get('equipo_id');
                        if (!$equipoId) {
                            return [];
                        }
                        
                        return Entrenamiento::where('equipo_id', $equipoId)
                            ->orderBy('fecha', 'desc')
                            ->get()
                            ->mapWithKeys(function ($e) {
                                $date = $e->fecha ? \Carbon\Carbon::parse($e->fecha)->format('d/m/Y') : 'Sin fecha';
                                $time = $e->hora ? \Carbon\Carbon::parse($e->hora)->format('H:i') : 'Sin hora';
                                return [$e->id => "{$e->nombre} ({$date} - {$time})"];
                            });
                    })
                    ->live()
                    ->disabled(fn (Get $get) => !$get('equipo_id'))
                    ->required()
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        if (!$state) {
                            $set('asistencias', []);
                            return;
                        }

                        $entrenamiento = Entrenamiento::find($state);
                        if (!$entrenamiento) {
                            $set('asistencias', []);
                            return;
                        }

                        // Get players assigned to this team actively
                        $players = EquipoUser::where('equipo_id', $entrenamiento->equipo_id)
                            ->where('user_id', '!=', auth()->id()) // Exclude coach themselves
                            ->where(function ($query) {
                                $query->whereNull('fecha_fin')
                                      ->orWhere('fecha_fin', '>=', now()->toDateString());
                            })
                            ->with('jugador')
                            ->get();

                        $asistenciasData = [];
                        foreach ($players as $pu) {
                            if (!$pu->jugador) {
                                continue;
                            }

                            // Look up existing attendance
                            $existing = AsistenciaEntrenamiento::where('entrenamiento_id', $state)
                                ->where('user_id', $pu->user_id)
                                ->first();

                            $asistenciasData[] = [
                                'user_id' => $pu->user_id,
                                'user_name' => $pu->jugador->name,
                                'presente' => $existing ? (bool) $existing->presente : false,
                            ];
                        }

                        // Sort players alphabetically by name
                        usort($asistenciasData, function ($a, $b) {
                            return strcasecmp($a['user_name'], $b['user_name']);
                        });

                        $set('asistencias', $asistenciasData);
                    }),

                Repeater::make('asistencias')
                    ->label('Lista de Jugadores')
                    ->schema([
                        Hidden::make('user_id'),
                        TextInput::make('user_name')
                            ->label('Jugador')
                            ->disabled()
                            ->dehydrated(false),
                        Toggle::make('presente')
                            ->label('Presente')
                            ->inline(false)
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark'),
                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->columns(2)
                    ->columnSpanFull()
                    ->visible(fn (Get $get) => (bool) $get('entrenamiento_id')),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar Asistencia')
                ->color('success')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();
        
        $entrenamientoId = $state['entrenamiento_id'] ?? null;
        $asistencias = $state['asistencias'] ?? [];

        if (!$entrenamientoId) {
            return;
        }

        $tenantId = auth()->user()->tenant_id;

        foreach ($asistencias as $asist) {
            AsistenciaEntrenamiento::updateOrCreate(
                [
                    'entrenamiento_id' => $entrenamientoId,
                    'user_id' => $asist['user_id'],
                ],
                [
                    'presente' => (bool) ($asist['presente'] ?? false),
                    'tenant_id' => $tenantId,
                ]
            );
        }

        Notification::make()
            ->title('Asistencia guardada')
            ->body('Los registros de asistencia se han guardado correctamente.')
            ->success()
            ->send();

        $this->redirect(static::getResource()::getUrl('index'));
    }
}
