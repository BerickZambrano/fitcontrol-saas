<?php

namespace App\Filament\Medico\Pages;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class MedicoProfile extends Page
{
    use InteractsWithForms;
    use WithFileUploads;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Mi Perfil';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Mi Perfil';

    protected string $view = 'filament.pages.medico-profile';

    public $name;
    public $email;
    public $avatar_url;
    public $two_factor_enabled;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')->label('Nombre')->required(),
            Forms\Components\TextInput::make('email')->label('Correo electrónico')->email()->required(),
            Forms\Components\FileUpload::make('avatar_url')
                ->label('Foto de perfil')
                ->image()
                ->avatar()
                ->disk('public')
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
        
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'avatar_url' => $user->avatar_url,
            'two_factor_enabled' => $user->two_factor_type === 'email',
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = Auth::user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        
        $avatar = $data['avatar_url'];
        $user->avatar_url = is_array($avatar) ? (reset($avatar) ?: null) : $avatar;

        $user->two_factor_type = ($data['two_factor_enabled'] ?? false) ? 'email' : 'none';
        $user->save();

        \Filament\Notifications\Notification::make()
            ->title('Perfil actualizado correctamente')
            ->success()
            ->send();
    }
}
