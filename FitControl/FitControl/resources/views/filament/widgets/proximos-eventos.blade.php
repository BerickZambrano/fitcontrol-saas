<div class="fi-wi-proximos-eventos">
    <div class="flex flex-col gap-6">
        @php
            $events = $this->getEvents();
        @endphp

        @if(count($events) > 0)
            @foreach($events as $event)
                <div class="flex items-center gap-6 rounded-xl border-2 border-gray-200 bg-white px-6 py-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                    {{-- Type badge --}}
                    <span
                        class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-base font-bold text-white"
                        style="background-color: {{ $event['color'] }};"
                    >
                        {{ $event['tipo'] }}
                    </span>

                    {{-- Event name --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-xl font-bold text-gray-900 dark:text-white truncate">
                            {{ $event['nombre'] }}
                        </p>
                        <p class="text-base text-gray-500 dark:text-gray-400 mt-1">
                            {{ $event['equipo'] }}
                        </p>
                    </div>

                    {{-- Date & Time --}}
                    <div class="text-right flex-shrink-0 hidden sm:block">
                        <p class="text-xl font-bold text-gray-700 dark:text-gray-200">
                            {{ \Carbon\Carbon::parse($event['fecha'])->format('d/m/Y') }}
                        </p>
                        <p class="text-base text-gray-400 mt-1">
                            {{ $event['hora'] !== '—' ? $event['hora'] : '' }}
                        </p>
                    </div>
                </div>
            @endforeach
        @else
            <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-white py-20 text-center dark:border-white/10 dark:bg-white/5">
                <x-filament::icon
                    icon="heroicon-o-calendar-days"
                    class="mb-4 h-14 w-14 text-gray-400"
                />
                <p class="text-xl font-bold text-gray-700 dark:text-gray-200">
                    No hay eventos esta semana
                </p>
                <p class="text-base text-gray-400 mt-2">
                    Programa un entrenamiento o partido para verlo aquí.
                </p>
            </div>
        @endif
    </div>
</div>
