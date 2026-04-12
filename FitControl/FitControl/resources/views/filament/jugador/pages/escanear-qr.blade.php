<x-filament-panels::page>
    <div class="max-w-md mx-auto">
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-qr-code" class="w-6 h-6 text-primary-600" />
                    Escanear código de asistencia
                </div>
            </x-slot>

            <x-slot name="description">
                Esta funcionalidad estará disponible próximamente. El entrenador generará un código QR
                en cada entrenamiento y tú podrás escanearlo para registrar tu asistencia.
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
                    Escanea el QR del entrenador para marcar tu asistencia
                </p>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
