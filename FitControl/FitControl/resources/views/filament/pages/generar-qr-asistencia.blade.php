<x-filament-panels::page>
    <div class="max-w-md mx-auto">
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-qr-code" class="w-6 h-6 text-primary-600" />
                    Generar QR de Asistencia
                </div>
            </x-slot>

            <x-slot name="description">
                Selecciona un entrenamiento para generar el código QR que los jugadores escanearán
                para registrar su asistencia. El código se renovará automáticamente cada 30 segundos.
            </x-slot>

            {{-- Placeholder --}}
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-24 h-24 bg-gray-100 dark:bg-white/5 rounded-2xl flex items-center justify-center mb-6">
                    <x-filament::icon icon="heroicon-o-qr-code" class="w-12 h-12 text-gray-400" />
                </div>
                <p class="text-lg font-bold text-gray-500 dark:text-gray-400">
                    Próximamente
                </p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">
                    Genera un QR dinámico para que los jugadores registren asistencia
                </p>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
