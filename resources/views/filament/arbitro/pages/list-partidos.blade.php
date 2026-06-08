<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
        {{-- Table on the left --}}
        <div class="lg:col-span-3">
            {{ $this->table }}
        </div>

        {{-- Help Card on the right --}}
        <div class="lg:col-span-1 flex flex-col gap-6">
            {{-- Instructions Card --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                {{-- Header --}}
                <div class="flex items-center gap-2 border-b border-gray-200 pb-4 dark:border-white/10">
                    <x-filament::icon
                        icon="heroicon-o-information-circle"
                        class="h-5 w-5 text-primary-500"
                    />
                    <h3 class="text-base font-bold text-gray-950 dark:text-white">
                        Guía de Arbitraje
                    </h3>
                </div>

                {{-- Steps Section --}}
                <div class="mt-4 flex flex-col gap-3">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-gray-400">
                        Flujo del Partido
                    </h4>

                    {{-- Step 1 --}}
                    <div class="flex flex-col gap-1 rounded-xl bg-warning-50 p-4 dark:bg-warning-950/20 border border-warning-200 dark:border-warning-900/30">
                        <div class="flex items-center gap-2 text-warning-700 dark:text-warning-400">
                            <span class="text-xs font-bold uppercase tracking-wider bg-warning-100 dark:bg-warning-900/50 px-2 py-0.5 rounded">Paso 1</span>
                            <span class="text-sm font-bold font-black">Aceptar</span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            Acepta o rechaza la asignación para confirmar tu asistencia.
                        </p>
                    </div>

                    {{-- Step 2 --}}
                    <div class="flex flex-col gap-1 rounded-xl bg-success-50 p-4 dark:bg-success-950/20 border border-success-200 dark:border-success-900/30">
                        <div class="flex items-center gap-2 text-success-700 dark:text-success-400">
                            <span class="text-xs font-bold uppercase tracking-wider bg-success-100 dark:bg-success-900/50 px-2 py-0.5 rounded">Paso 2</span>
                            <span class="text-sm font-bold font-black">Iniciar</span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            Haz clic al comenzar el encuentro para cambiar el estado a "En Juego".
                        </p>
                    </div>

                    {{-- Step 3 --}}
                    <div class="flex flex-col gap-1 rounded-xl bg-primary-50 p-4 dark:bg-primary-950/20 border border-primary-200 dark:border-primary-900/30">
                        <div class="flex items-center gap-2 text-primary-700 dark:text-primary-400">
                            <span class="text-xs font-bold uppercase tracking-wider bg-primary-100 dark:bg-primary-900/50 px-2 py-0.5 rounded">Paso 3</span>
                            <span class="text-sm font-bold font-black">Finalizar</span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            Registra el marcador y las incidencias en el formulario de cierre.
                        </p>
                    </div>
                </div>

                {{-- Marcador Section --}}
                <div class="mt-6 border-t border-gray-200 pt-4 dark:border-white/10 flex flex-col gap-3">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-gray-400">
                        Cómo Registrar el Marcador
                    </h4>

                    <div class="flex flex-col gap-1 rounded-xl bg-danger-50 p-4 dark:bg-danger-950/20 border border-danger-200 dark:border-danger-900/30">
                        <div class="flex items-center gap-2 text-danger-700 dark:text-danger-400 font-bold text-sm">
                            <x-filament::icon
                                icon="heroicon-o-exclamation-triangle"
                                class="h-4 w-4"
                            />
                            <span>Orden del Marcador</span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            Escribe siempre: <strong class="text-gray-900 dark:text-white">Goles Local - Goles Visitante</strong> (ej: <span class="bg-gray-100 dark:bg-white/10 px-1 py-0.5 rounded font-mono">2-1</span>).
                        </p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 italic">
                            * Verifica en la tabla cuál equipo es el Local y cuál es el Visitante antes de ingresar el marcador.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
