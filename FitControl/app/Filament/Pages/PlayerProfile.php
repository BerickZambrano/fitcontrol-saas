<?php

namespace App\Filament\Pages;

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
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->avatar = $user->avatar ?? null;

        // Inicializa el formulario con los datos actuales
        $this->form->fill([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
        ]);
    }

    public function save(): void
    {
        $this->validateForm();

        $user = Auth::user();
        $user->name = $this->form->name;
        $user->email = $this->form->email;
        $user->phone = $this->form->phone;
        if ($this->form->avatar) {
            $user->avatar = $this->form->avatar;
        }
        $user->save();

        $this->notify('success', 'Perfil actualizado correctamente');
    }
}