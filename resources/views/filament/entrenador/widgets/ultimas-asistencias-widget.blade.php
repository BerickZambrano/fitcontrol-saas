<x-filament-widgets::widget>
    @php
        $asistencias = $this->getUltimasAsistencias();
    @endphp

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 flex flex-col gap-4">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3">
            <h3 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
                <span>📋</span> Últimas Asistencias
            </h3>
            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Sesiones recientes</span>
        </div>

        @if($asistencias->isEmpty())
            <div class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm">
                No se han registrado asistencias aún.
            </div>
        @else
            <div class="flex flex-col gap-3">
                @foreach($asistencias as $asist)
                    @php
                        $fechaFormatted = \Carbon\Carbon::parse($asist['fecha'])->translatedFormat('d \d\e M, Y');
                    @endphp
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $asist['nombre'] }}</span>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">{{ $asist['equipo'] }}</span>
                                <span class="text-[10px] text-gray-300 dark:text-gray-700">|</span>
                                <span class="text-[11px] text-gray-500 dark:text-gray-400 font-medium">📅 {{ $fechaFormatted }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex flex-col items-end">
                                <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $asist['presentes'] }} / {{ $asist['total'] }}</span>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-semibold">Presentes</span>
                            </div>
                            <span class="text-xs font-black px-2.5 py-1 rounded-lg @if($asist['porcentaje'] >= 80) bg-green-100 text-green-800 dark:bg-green-950/50 dark:text-green-300 @elseif($asist['porcentaje'] >= 50) bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:text-amber-300 @else bg-red-100 text-red-800 dark:bg-red-950/50 dark:text-red-300 @endif">
                                {{ $asist['porcentaje'] }}%
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-widgets::widget>
