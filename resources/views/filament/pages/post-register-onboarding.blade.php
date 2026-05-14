<x-filament-panels::page>
    <div class="max-w-3xl mx-auto">
        {{-- Header --}}
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Bienvenido a FitControl
            </h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Configura tu club en 5 sencillos pasos y empieza a gestionar tu equipo desde hoy.
            </p>
        </div>

        {{-- Form --}}
        <x-filament::section>
            <form wire:submit="complete">
                {{ $this->form }}

                <div class="flex justify-end mt-6">
                    <x-filament::button type="submit" color="primary" size="lg">
                        ¡Ir al Dashboard!
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>
    </div>
</x-filament-panels::page>
