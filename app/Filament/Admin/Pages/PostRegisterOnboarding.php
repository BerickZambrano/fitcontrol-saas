<?php

namespace App\Filament\Admin\Pages;

use App\Models\Equipo;
use App\Models\Entrenamiento;
use App\Models\User;
use App\Models\JugadorPerfil;
use App\Models\EquipoUser;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PostRegisterOnboarding extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = '';
    protected static ?string $title = 'Configura tu club';
    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.post-register-onboarding';

    /**
     * Only accessible by users whose tenant needs onboarding.
     */
    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (!$user || $user->hasRole('super_admin')) {
            return false;
        }

        $tenant = $user->tenant;

        return $tenant && $tenant->needsOnboarding();
    }

    // ── Form state ──────────────────────────────────────────────
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // ── Step 1: Club Info ──────────────────────
                Section::make('Paso 1: Datos del club')
                    ->description('Personaliza la información de tu club')
                    ->schema([
                        TextInput::make('club_nombre')
                            ->label('Nombre del club')
                            ->default(fn () => auth()->user()?->tenant?->nombre)
                            ->required()
                            ->maxLength(255),

                        TextInput::make('club_nombre_corto')
                            ->label('Nombre corto / Siglas')
                            ->default(fn () => auth()->user()?->tenant?->nombre_corto)
                            ->required()
                            ->maxLength(100),

                        Select::make('club_tipo')
                            ->label('Tipo de club')
                            ->options([
                                'formativo' => 'Formativo',
                                'amateur' => 'Amateur',
                                'profesional' => 'Profesional',
                            ])
                            ->default(fn () => auth()->user()?->tenant?->tipo_club)
                            ->required(),

                        FileUpload::make('club_escudo')
                            ->label('Escudo del club')
                            ->disk('public')
                            ->directory('tenants/logos')
                            ->image()
                            ->maxSize(2048)
                            ->helperText('PNG o JPG, máximo 2MB. Opcional.'),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                // ── Step 2: First Team ─────────────────────
                Section::make('Paso 2: Crea tu primer equipo')
                    ->description('El primer equipo de tu club')
                    ->schema([
                        TextInput::make('equipo_nombre')
                            ->label('Nombre del equipo')
                            ->placeholder('Ej: Plantel Principal, Sub-17, etc.')
                            ->required()
                            ->maxLength(255),

                        Select::make('equipo_categoria')
                            ->label('Categoría')
                            ->options([
                                'profesional' => 'Profesional',
                                'amateur' => 'Amateur',
                                'formativo' => 'Formativo',
                            ])
                            ->default('amateur')
                            ->required(),

                        TextInput::make('equipo_ubicacion')
                            ->label('Ubicación / Sede')
                            ->placeholder('Ej: Cancha Municipal, Polideportivo Norte')
                            ->required()
                            ->maxLength(255),

                        Checkbox::make('equipo_terminos')
                            ->label('Acepto los Términos y Condiciones de FitControl')
                            ->required()
                            ->inline(false),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                // ── Step 3: Add Players ────────────────────
                Section::make('Paso 3: Agrega jugadores')
                    ->description('Inscribe al menos un jugador')
                    ->schema([
                        TextInput::make('jugador_nombre')
                            ->label('Nombre del jugador')
                            ->placeholder('Nombre completo')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('jugador_email')
                            ->label('Correo electrónico')
                            ->placeholder('correo@ejemplo.com')
                            ->email()
                            ->nullable(),

                        Select::make('jugador_posicion')
                            ->label('Posición')
                            ->options([
                                'Portero' => 'Portero',
                                'Defensa' => 'Defensa',
                                'Mediocampista' => 'Mediocampista',
                                'Delantero' => 'Delantero',
                            ])
                            ->nullable(),

                        TextInput::make('jugador_dorsal')
                            ->label('Dorsal')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(99)
                            ->nullable(),

                        Select::make('jugador_pierna')
                            ->label('Pierna hábil')
                            ->options([
                                'derecha' => 'Derecha',
                                'izquierda' => 'Izquierda',
                                'ambas' => 'Ambas',
                            ])
                            ->nullable(),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                // ── Step 4: First Training ─────────────────
                Section::make('Paso 4: Programa un entrenamiento')
                    ->description('Tu primera sesión programada')
                    ->schema([
                        TextInput::make('entrenamiento_nombre')
                            ->label('Nombre del entrenamiento')
                            ->placeholder('Ej: Entrenamiento general, Pretemporada')
                            ->required()
                            ->maxLength(100),

                        DatePicker::make('entrenamiento_fecha')
                            ->label('Fecha')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->minDate(now()),

                        TimePicker::make('entrenamiento_hora')
                            ->label('Hora')
                            ->required()
                            ->seconds(false),

                        TextInput::make('entrenamiento_ubicacion')
                            ->label('Lugar / Ubicación')
                            ->placeholder('Ej: Cancha sintética Norte')
                            ->required()
                            ->maxLength(100),
                    ])
                    ->collapsible()
                    ->collapsed(false),
            ])
            ->statePath('data');
    }

    /**
     * Execute all creation in a single transaction.
     */
    public function complete(): void
    {
        $formData = $this->form->getState();
        $user = auth()->user();
        $tenant = $user->tenant;

        if (!$tenant) {
            Notification::make()
                ->title('Error: no se encontró tu club.')
                ->danger()
                ->send();

            return;
        }

        DB::beginTransaction();

        try {
            // ── 1. Update tenant info ──────────────────────────
            $tenantUpdate = [
                'nombre' => $formData['club_nombre'] ?? $tenant->nombre,
                'nombre_corto' => $formData['club_nombre_corto'] ?? $tenant->nombre_corto,
                'tipo_club' => $formData['club_tipo'] ?? $tenant->tipo_club,
            ];

            if (!empty($formData['club_escudo'])) {
                $tenantUpdate['escudo_url'] = $formData['club_escudo'];
            }

            $tenant->update($tenantUpdate);

            // ── 2. Create first team ───────────────────────────
            $equipo = Equipo::create([
                'tenant_id' => $tenant->id,
                'nombre' => $formData['equipo_nombre'],
                'categoria' => $formData['equipo_categoria'],
                'ubi_equipo' => $formData['equipo_ubicacion'] ?? '',
            ]);

            // ── 3. Create player user + profile ────────────────
            $jugadorEmail = $formData['jugador_email'] ?? null;
            $jugadorUser = User::create([
                'tenant_id' => $tenant->id,
                'name' => $formData['jugador_nombre'],
                'email' => $jugadorEmail ?? 'jugador-' . uniqid() . '@fitcontrol.temp',
                'password' => Hash::make('fitcontrol123'),
            ]);
            $jugadorUser->assignRole('Jugador');

            JugadorPerfil::create([
                'tenant_id' => $tenant->id,
                'user_id' => $jugadorUser->id,
                'posicion' => $formData['jugador_posicion'] ?? null,
                'dorsal' => $formData['jugador_dorsal'] ?? null,
                'pierna_habil' => $formData['jugador_pierna'] ?? null,
            ]);

            // Link player to team
            EquipoUser::create([
                'tenant_id' => $tenant->id,
                'equipo_id' => $equipo->id,
                'user_id' => $jugadorUser->id,
                'fecha_inicio' => now()->toDateString(),
            ]);

            // ── 4. Create first training ───────────────────────
            Entrenamiento::create([
                'tenant_id' => $tenant->id,
                'equipo_id' => $equipo->id,
                'nombre' => $formData['entrenamiento_nombre'],
                'fecha' => $formData['entrenamiento_fecha'],
                'hora' => $formData['entrenamiento_hora'],
                'ubicacion' => $formData['entrenamiento_ubicacion'],
            ]);

            // ── 5. Mark onboarding as completed ────────────────
            $tenant->update(['onboarding_completed' => true]);

            DB::commit();

            Notification::make()
                ->title('🎉 ¡Club configurado exitosamente!')
                ->body('Ya tienes tu primer equipo, un jugador y un entrenamiento programado.')
                ->success()
                ->send();

            redirect()->route('filament.admin.pages.dashboard');
        } catch (\Throwable $e) {
            DB::rollBack();

            Notification::make()
                ->title('Error al configurar el club')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
