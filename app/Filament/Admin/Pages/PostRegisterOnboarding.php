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
                \Filament\Schemas\Components\Wizard::make([
                    // ── Step 1: Club Info ──────────────────────
                    \Filament\Schemas\Components\Wizard\Step::make('Identidad del Club')
                        ->description('Define el nombre y estilo de tu marca')
                        ->icon('heroicon-o-building-office')
                        ->schema([
                            TextInput::make('club_nombre')
                                ->label('Nombre del club')
                                ->default(fn () => auth()->user()?->tenant?->nombre)
                                ->required()
                                ->validationMessages(['required' => 'El nombre del club es obligatorio.'])
                                ->maxLength(255),

                            TextInput::make('club_nombre_corto')
                                ->label('Nombre corto / Siglas')
                                ->default(fn () => auth()->user()?->tenant?->nombre_corto)
                                ->required()
                                ->validationMessages(['required' => 'El nombre corto es obligatorio.'])
                                ->maxLength(100),

                            \Filament\Forms\Components\ColorPicker::make('primary_color')
                                ->label('Color Principal del Aplicativo')
                                ->helperText('Este color se usará en botones, iconos y menús para tu club.')
                                ->default('#ef4444') // Rojo por defecto
                                ->required()
                                ->validationMessages(['required' => 'El color principal es obligatorio.']),

                            Select::make('club_tipo')
                                ->label('Tipo de club')
                                ->options([
                                    'formativo' => 'Formativo',
                                    'amateur' => 'Amateur',
                                    'profesional' => 'Profesional',
                                ])
                                ->default(fn () => auth()->user()?->tenant?->tipo_club)
                                ->required()
                                ->validationMessages(['required' => 'El tipo de club es obligatorio.']),

                            FileUpload::make('club_escudo')
                                ->label('Escudo del club')
                                ->disk('public')
                                ->directory('tenants/logos')
                                ->image()
                                ->maxSize(10240), // Aumentado a 10MB
                                
                            Checkbox::make('terms')
                                ->label(fn () => new \Illuminate\Support\HtmlString('Acepto los <a href="/terminos-y-condiciones" target="_blank" class="text-primary-600 font-bold hover:underline">Términos y Condiciones</a> y confirmo tener autorización para registrar los datos del club.'))
                                ->accepted()
                                ->required()
                                ->columnSpanFull(),
                        ])->columns(2),

                    // ── Step 2: First Team ─────────────────────
                    \Filament\Schemas\Components\Wizard\Step::make('Primer Equipo')
                        ->description('Crea el primer grupo de trabajo')
                        ->icon('heroicon-o-users')
                        ->schema([
                            TextInput::make('equipo_nombre')
                                ->label('Nombre del equipo')
                                ->placeholder('Ej: Plantel Principal')
                                ->required()
                                ->validationMessages(['required' => 'El nombre del equipo es obligatorio.']),

                            Select::make('equipo_categoria')
                                ->label('Categoría')
                                ->options([
                                    'profesional' => 'Profesional',
                                    'amateur' => 'Amateur',
                                    'formativo' => 'Formativo',
                                ])
                                ->default('amateur')
                                ->required()
                                ->validationMessages(['required' => 'La categoría es obligatoria.']),

                            TextInput::make('equipo_ubicacion')
                                ->label('Ubicación / Sede')
                                ->required()
                                ->validationMessages(['required' => 'La ubicación es obligatoria.']),
                        ])->columns(2),

                    // ── Step 3: First Player ────────────────────
                    \Filament\Schemas\Components\Wizard\Step::make('Jugadores')
                        ->description('Inscribe a tu primer deportista (Opcional)')
                        ->icon('heroicon-o-user-plus')
                        ->schema([
                            TextInput::make('jugador_nombre')
                                ->label('Nombre completo')
                                ->placeholder('Ej: Juan Pérez'),

                            TextInput::make('jugador_email')
                                ->label('Correo (Opcional)')
                                ->email(),

                            Select::make('jugador_posicion')
                                ->label('Posición')
                                ->options([
                                    'Portero' => 'Portero',
                                    'Defensa' => 'Defensa',
                                    'Mediocampista' => 'Mediocampista',
                                    'Delantero' => 'Delantero',
                                ]),

                            TextInput::make('jugador_dorsal')
                                ->label('Dorsal')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(99),
                        ])->columns(2),

                    // ── Step 4: Training ─────────────────
                    \Filament\Schemas\Components\Wizard\Step::make('Planificación')
                        ->description('Programa tu primer entrenamiento (Opcional)')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            TextInput::make('entrenamiento_nombre')
                                ->label('Nombre de la sesión'),

                            DatePicker::make('entrenamiento_fecha')
                                ->label('Fecha')
                                ->native(false),

                            TimePicker::make('entrenamiento_hora')
                                ->label('Hora')
                                ->seconds(false),

                            TextInput::make('entrenamiento_ubicacion')
                                ->label('Lugar'),
                        ])->columns(2),
                ])
                ->submitAction(new \Illuminate\Support\HtmlString('<button type="submit" class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 disabled:pointer-events-none disabled:opacity-70 rounded-lg fi-color-primary fi-btn-color-primary bg-primary-600 text-white shadow-sm hover:bg-primary-500 focus-visible:ring-primary-500/50 dark:bg-primary-500 dark:hover:bg-primary-400 dark:focus-visible:ring-primary-400/50 py-2 px-4 inline-grid">Finalizar Configuración</button>'))
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
                'colores_oficiales' => [
                    'primary' => $formData['primary_color'] ?? '#ef4444',
                ],
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

            // ── 3. Create player user + profile (Optional) ──────
            if (!empty($formData['jugador_nombre'])) {
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
            }

            // ── 4. Create first training (Optional) ────────────
            if (!empty($formData['entrenamiento_nombre']) && !empty($formData['entrenamiento_fecha'])) {
                Entrenamiento::create([
                    'tenant_id' => $tenant->id,
                    'equipo_id' => $equipo->id,
                    'nombre' => $formData['entrenamiento_nombre'],
                    'fecha' => $formData['entrenamiento_fecha'],
                    'hora' => $formData['entrenamiento_hora'] ?? '08:00:00',
                    'ubicacion' => $formData['entrenamiento_ubicacion'] ?? $formData['equipo_ubicacion'],
                ]);
            }

            // ── 5. Mark onboarding as completed ────────────────
            $tenant->update(['onboarding_completed' => true]);

            DB::commit();

            Notification::make()
                ->title('🎉 ¡Club configurado exitosamente!')
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
