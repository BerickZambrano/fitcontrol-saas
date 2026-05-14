<x-filament-widgets::widget>
    <div class="fi-wi-proximos-eventos w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 w-full">
            @php
                $events = $this->getEvents();
            @endphp

            @forelse($events as $event)
                <div class="relative group overflow-hidden rounded-2xl bg-white p-5 shadow-sm border border-gray-100 transition-all hover:shadow-md dark:bg-white/5 dark:border-white/10 w-full">
                    {{-- Accent bar --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1.5" style="background-color: {{ $event['color'] }};"></div>
                    
                    <div class="flex items-center gap-4 w-full">
                        {{-- Icon Container --}}
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                            @if($event['tipo'] === 'Entrenamiento')
                                <x-filament::icon icon="heroicon-o-calendar-days" class="h-6 w-6 text-gray-500" />
                            @else
                                <x-filament::icon icon="heroicon-o-trophy" class="h-6 w-6 text-gray-500" />
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-black uppercase tracking-wider text-white" style="background-color: {{ $event['color'] }};">
                                    {{ $event['tipo'] }}
                                </span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest truncate">
                                    {{ $event['equipo'] }}
                                </span>
                            </div>
                            <h4 class="text-sm font-black text-gray-900 dark:text-white truncate uppercase tracking-tight">
                                {{ $event['nombre'] }}
                            </h4>
                        </div>

                        {{-- Date/Time --}}
                        <div class="shrink-0 text-right ml-auto">
                            <div class="text-lg font-black text-gray-900 dark:text-white leading-none">
                                {{ \Carbon\Carbon::parse($event['fecha'])->format('d/m') }}
                            </div>
                            <div class="text-[10px] font-bold text-gray-400 uppercase mt-1">
                                {{ $event['hora'] !== '—' ? $event['hora'] : '' }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 py-12 text-center dark:border-white/10 w-full">
                    <x-filament::icon icon="heroicon-o-calendar" class="mb-3 h-10 w-10 text-gray-300" />
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">Sin eventos programados</p>
                </div>
            @endforelse
        </div>
    </div>
</x-filament-widgets::widget>
