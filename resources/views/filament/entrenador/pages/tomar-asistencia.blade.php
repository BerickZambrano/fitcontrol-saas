<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 flex flex-wrap gap-4 justify-start">
            @foreach ($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </form>
</x-filament-panels::page>
