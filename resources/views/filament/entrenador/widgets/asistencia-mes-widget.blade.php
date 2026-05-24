<x-filament-widgets::widget>
    @php
        $asistencias = $this->getAsistenciaEquipos();
        $mesActual = \Carbon\Carbon::now()->translatedFormat('F');
    @endphp

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 flex flex-col gap-4">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3">
            <h3 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
                <span>📈</span> Asistencia de {{ ucfirst($mesActual) }}
            </h3>
            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Porcentaje por equipo</span>
        </div>

        @if($asistencias->isEmpty())
            <div class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm">
                No hay equipos asignados.
            </div>
        @else
            <div class="flex flex-col gap-4">
                @foreach($asistencias as $asist)
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $asist['equipo'] }}</span>
                            @if(is_null($asist['tasa']))
                                <span class="text-xs text-gray-400 font-medium">Sin sesiones este mes</span>
                            @else
                                <span class="text-xs font-bold @if($asist['tasa'] >= 80) text-green-600 dark:text-green-400 @elseif($asist['tasa'] >= 50) text-amber-500 dark:text-amber-400 @else text-red-500 dark:text-red-400 @endif">
                                    {{ $asist['tasa'] }}% ({{ $asist['sesiones'] }} {{ $asist['sesiones'] === 1 ? 'sesión' : 'sesiones' }})
                                </span>
                            @endif
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2">
                            <div class="h-2 rounded-full @if(is_null($asist['tasa'])) bg-gray-300 dark:bg-gray-700 w-0 @elseif($asist['tasa'] >= 80) bg-green-500 @elseif($asist['tasa'] >= 50) bg-amber-500 @else bg-red-500 @endif"
                                 style="width: {{ $asist['tasa'] ?? 0 }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-widgets::widget>
