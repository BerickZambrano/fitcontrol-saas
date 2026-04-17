<x-filament::page>
    <div class="space-y-6">
        <h1 class="text-2xl font-bold">Mi Perfil</h1>

        {{-- Información del usuario --}}
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Nombre --}}
                {{ $this->form->schema([
                    \Filament\Forms\Components\TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                ]) }}

                {{-- Correo electrónico --}}
                {{ $this->form->schema([
                    \Filament\Forms\Components\TextInput::make('email')
                        ->label('Correo electrónico')
                        ->email()
                        ->required()
                ]) }}

                {{-- Teléfono --}}
                {{ $this->form->schema([
                    \Filament\Forms\Components\TextInput::make('phone')
                        ->label('Teléfono')
                        ->tel()
                ]) }}

                {{-- Foto de perfil --}}
                {{ $this->form->schema([
                    \Filament\Forms\Components\FileUpload::make('avatar')
                        ->label('Foto de perfil')
                        ->image()
                        ->directory('avatars')
                        ->maxSize(1024) {{-- 1MB --}}
                ]) }}
            </div>

            <hr class="border-neutral-200 dark:border-neutral-800">

            {{-- Seguridad --}}
            <div class="space-y-4">
                <h2 class="text-xl font-semibold">Seguridad</h2>
                <p class="text-sm text-neutral-500">Configura la seguridad de tu cuenta</p>
                
                {{ $this->form->schema([
                    \Filament\Forms\Components\Toggle::make('two_factor_enabled')
                        ->label('Autenticación por Correo Electrónico')
                        ->helperText('Recibe un código de seguridad en tu correo electrónico al iniciar sesión.')
                        ->onIcon('heroicon-m-envelope')
                        ->offIcon('heroicon-m-x-mark')
                ]) }}
            </div>

            {{-- Botón Guardar --}}
            <x-filament::button type="submit">
                Guardar cambios
            </x-filament::button>
        </form>
    </div>
</x-filament::page>