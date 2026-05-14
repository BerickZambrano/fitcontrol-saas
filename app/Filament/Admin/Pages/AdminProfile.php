<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class AdminProfile extends Page
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $title = 'Mi Perfil de Administrador';
    protected static bool $shouldRegisterNavigation = false;

    public static function getLabel(): string
    {
        return 'Mi Perfil';
    }

    protected string $view = 'filament.admin.pages.admin-profile';

    public $name;
    public $email;
    public $avatar_url;
    public $two_factor_enabled;
    public $primary_color;

    protected function getFormSchema(): array
    {
        return [
            \Filament\Schemas\Components\Section::make('Información del Administrador')
                ->description('Gestiona tus datos personales y foto de perfil')
                ->schema([
                    Forms\Components\TextInput::make('name')->label('Nombre')->required(),
                    Forms\Components\TextInput::make('email')->label('Correo electrónico')->email()->required(),
                    Forms\Components\FileUpload::make('avatar_url')
                        ->label('Foto de Perfil')
                        ->image()
                        ->avatar()
                        ->disk('public')
                        ->directory('avatars')
                        ->maxSize(2048),
                    Forms\Components\ColorPicker::make('primary_color')
                        ->label('Color de la Aplicación')
                        ->helperText('Cambia el color principal de todo el sistema para tu equipo.'),
                ])->columns(2),

            \Filament\Schemas\Components\Section::make('Seguridad Avanzada')
                ->description('Configura la protección de tu cuenta')
                ->schema([
                    Forms\Components\Toggle::make('two_factor_enabled')
                        ->label('Autenticación por Correo Electrónico')
                        ->helperText('Recibe un código de seguridad en tu correo electrónico al iniciar sesión.')
                        ->onIcon('heroicon-m-envelope')
                        ->offIcon('heroicon-m-x-mark')
                        ->live(),
                ]),
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'avatar_url' => $user->avatar_url,
            'two_factor_enabled' => $user->two_factor_type === 'email',
            'primary_color' => $user->tenant?->colores_oficiales['primary'] ?? '#3b82f6',
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = Auth::user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->avatar_url = $data['avatar_url'];
        $user->two_factor_type = ($data['two_factor_enabled'] ?? false) ? 'email' : 'none';
        $user->save();

        if ($user->tenant) {
            $currentColors = is_array($user->tenant->colores_oficiales) ? $user->tenant->colores_oficiales : [];
            
            $user->tenant->update([
                'colores_oficiales' => array_merge($currentColors, [
                    'primary' => $data['primary_color'],
                ]),
            ]);
        }

        \Filament\Notifications\Notification::make()
            ->title('Perfil de administrador actualizado')
            ->success()
            ->send();
    }
}
