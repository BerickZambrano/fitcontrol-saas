<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Formulario de generación --}}
        <div class="lg:col-span-2">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-filament::icon
                            icon="heroicon-o-chart-bar-square"
                            class="w-6 h-6 text-primary-600"
                        />
                        Generar Nuevo Reporte
                    </div>
                </x-slot>

                <x-slot name="description">
                    Selecciona el tipo de reporte, equipo y período para generar el documento.
                </x-slot>

                <form wire:submit="generarReporte" class="space-y-6">
                    {{ $this->form }}

                    <div class="flex justify-end">
                        <x-filament::button type="submit" icon="heroicon-o-document-arrow-down">
                            Generar Reporte
                        </x-filament::button>
                    </div>
                </form>
            </x-filament::section>
        </div>

        {{-- Panel informativo --}}
        <div class="lg:col-span-1">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-filament::icon
                            icon="heroicon-o-information-circle"
                            class="w-5 h-5 text-primary-600"
                        />
                        Tipos de Reportes
                    </div>
                </x-slot>

                <div class="space-y-4 text-sm">
                    <div class="p-3 bg-blue-50 dark:bg-blue-950/30 rounded-lg">
                        <div class="font-semibold text-blue-700 dark:text-blue-400">📈 Rendimiento</div>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Goles, asistencias, tarjetas, minutos jugados y evaluación por jugador.
                        </p>
                    </div>

                    <div class="p-3 bg-green-50 dark:bg-green-950/30 rounded-lg">
                        <div class="font-semibold text-green-700 dark:text-green-400">📅 Asistencia</div>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Presente/ausente por entrenamiento con porcentajes y tendencias.
                        </p>
                    </div>

                    <div class="p-3 bg-amber-50 dark:bg-amber-950/30 rounded-lg">
                        <div class="font-semibold text-amber-700 dark:text-amber-400">💰 Financiero</div>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Pagos cobrados, pendientes, rechazados y resumen por estado.
                        </p>
                    </div>

                    <div class="p-3 bg-red-50 dark:bg-red-950/30 rounded-lg">
                        <div class="font-semibold text-red-700 dark:text-red-400">🏥 Médico</div>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Lesiones, gravedad, jugadores aptos/no aptos y tipos de lesión.
                        </p>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section class="mt-6">
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-filament::icon
                            icon="heroicon-o-folder-arrow-down"
                            class="w-5 h-5 text-primary-600"
                        />
                        Formatos
                    </div>
                </x-slot>

                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">📄</span>
                        <span><strong>PDF</strong> — Listo para imprimir y enviar</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-lg">📊</span>
                        <span><strong>Excel</strong> — Para análisis y filtrado</span>
                    </div>
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
