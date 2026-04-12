<div class="fi-wi-proximo-compromiso">
    @php
        $event = $this->getNextEvent();
    @endphp

    <div class="rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-2xl">📅</span>
            <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">
                Próximo compromiso
            </h3>
        </div>

        @if($event)
            <div class="flex items-center gap-4">
                {{-- Type badge --}}
                <span
                    class="inline-flex items-center rounded-full px-3 py-1.5 text-sm font-bold text-white flex-shrink-0"
                    style="background-color: {{ $event['color'] }};"
                >
                    {{ $event['tipo'] }}
                </span>

                {{-- Event info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-xl font-black text-gray-900 dark:text-white">
                        {{ $event['nombre'] }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ $event['equipo'] }}
                    </p>
                </div>

                {{-- Date & Time --}}
                <div class="text-right flex-shrink-0">
                    <p class="text-xl font-bold text-gray-700 dark:text-gray-200">
                        {{ \Carbon\Carbon::parse($event['fecha'])->format('d/m') }}
                    </p>
                    <p class="text-base text-gray-500 dark:text-gray-400">
                        {{ $event['hora'] !== '—' ? $event['hora'] : '' }}
                    </p>
                </div>
            </div>

            @if($event['ubicacion'] !== '—')
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-3 flex items-center gap-1">
                    📍 {{ $event['ubicacion'] }}
                </p>
            @endif
        @else
            <div class="text-center py-8">
                <p class="text-lg font-bold text-gray-400 dark:text-gray-500">
                    No hay compromisos esta semana
                </p>
            </div>
        @endif
    </div>
</div>
