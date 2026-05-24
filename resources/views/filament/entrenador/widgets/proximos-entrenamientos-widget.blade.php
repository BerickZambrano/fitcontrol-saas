<x-filament-widgets::widget>
    @php
        $entrenamientos = $this->getEntrenamientos();
    @endphp

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 flex flex-col gap-4">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3">
            <h3 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
                <span>🏋️</span> Próximos Entrenamientos
            </h3>
            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Siguientes 5 sesiones</span>
        </div>

        @if($entrenamientos->isEmpty())
            <div class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm">
                No hay entrenamientos programados.
            </div>
        @else
            <div class="flex flex-col gap-3">
                @foreach($entrenamientos as $e)
                    @php
                        $fechaFormatted = \Carbon\Carbon::parse($e->fecha)->translatedFormat('d \d\e M, Y');
                        $horaFormatted = \Carbon\Carbon::parse($e->hora)->format('H:i');
                    @endphp
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors border border-gray-100 dark:border-gray-800">
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $e->nombre }}</span>
                            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium mt-0.5">{{ $e->equipo?->nombre ?? 'Sin equipo' }}</span>
                        </div>
                        <div class="flex flex-col items-end gap-1 text-right">
                            <span class="text-xs font-bold text-gray-800 dark:text-gray-200 flex items-center gap-1">
                                📅 {{ $fechaFormatted }}
                            </span>
                            <span class="text-[11px] text-gray-500 dark:text-gray-400 flex items-center gap-1 font-medium">
                                ⏰ {{ $horaFormatted }} | 📍 {{ $e->instalacion?->nombre ?? $e->ubicacion }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-widgets::widget>
