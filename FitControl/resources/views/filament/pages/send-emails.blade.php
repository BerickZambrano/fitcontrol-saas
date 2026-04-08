<x-filament::page>
    {{ $this->form }}

    <div class="mt-4">
        <x-filament::button wire:click="submit" type="button">
            Enviar Correos
        </x-filament::button>
    </div>
</x-filament::page>