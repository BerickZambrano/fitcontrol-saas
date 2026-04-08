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

            {{-- Botón Guardar --}}
            <x-filament::button type="submit">
                Guardar cambios
            </x-filament::button>
        </form>
    </div>
</x-filament::page>