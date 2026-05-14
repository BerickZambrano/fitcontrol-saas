<?php

namespace App\Filament\Jugador\Pages;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Enums\NavigationGroup;
use UnitEnum;
use BackedEnum;

class PlayerProfile extends Page
{
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Mi Perfil';
    protected static ?string $title = 'Mi Perfil';

    protected string $view = 'filament.pages.player-profile';

    public $name;
    public $email;
    public $phone;
    public $avatar;
    public $two_factor_enabled;

    // Inicializa el formulario
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')->label('Nombre')->required(),
            Forms\Components\TextInput::make('email')->label('Correo electrónico')->email()->required(),
            Forms\Components\TextInput::make('phone')->label('Teléfono')->tel(),
            Forms\Components\FileUpload::make('avatar')
                ->label('Foto de perfil')
                ->image()
                ->directory('avatars')
                ->maxSize(1024),
            \Filament\Schemas\Components\Section::make('Seguridad')
                ->description('Configura la seguridad de tu cuenta')
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
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->avatar = $user->avatar ?? null;
        $this->two_factor_enabled = $user->two_factor_type === 'email';

        // Inicializa el formulario con los datos actuales
        $this->form->fill([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'two_factor_enabled' => $this->two_factor_enabled,
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = Auth::user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        // Los campos phone y avatar no existen en la tabla users en este momento
        $user->two_factor_type = ($data['two_factor_enabled'] ?? false) ? 'email' : 'none';
        $user->save();

        \Filament\Notifications\Notification::make()
            ->title('Perfil actualizado correctamente')
            ->success()
            ->send();
    }
}