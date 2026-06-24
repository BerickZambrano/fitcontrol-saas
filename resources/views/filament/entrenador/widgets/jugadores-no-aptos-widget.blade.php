<x-filament-widgets::widget>
    @php
        $lesionados = $this->getJugadoresNoAptos();
    @endphp

    <x-filament::section>
        <x-slot name="heading">
            <span class="flex items-center gap-2">
                <span class="text-xl">🚑</span> Jugadores de Baja / No Aptos
            </span>
        </x-slot>

        <x-slot name="headerEnd">
            <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest bg-gray-100 dark:bg-white/5 px-2 py-0.5 rounded">
                Historial médico activo
            </span>
        </x-slot>

        @if($lesionados->isEmpty())
            <div class="text-center py-8 text-gray-500 dark:text-gray-400 text-sm flex flex-col items-center gap-2">
                <span class="text-3xl">✅</span>
                <span class="font-bold">Todos los jugadores están aptos y listos para jugar. ¡Buen trabajo! 👍</span>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                @foreach($lesionados as $l)
                    @php
                        $inicio = \Carbon\Carbon::parse($l->fecha_inicio)->format('d/m/Y');
                        $fin = $l->fecha_fin ? \Carbon\Carbon::parse($l->fecha_fin)->format('d/m/Y') : 'Indefinido';
                        $avatarColor = match($l->gravedad) {
                            'grave' => 'bg-red-500/10 text-red-500 border-red-500/20',
                            'media' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                            default => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                        };
                        $gravedadColor = match($l->gravedad) {
                            'grave' => 'bg-red-500/15 text-red-700 dark:text-red-400 border-red-500/10',
                            'media' => 'bg-amber-500/15 text-amber-700 dark:text-amber-400 border-amber-500/10',
                            default => 'bg-blue-500/15 text-blue-700 dark:text-blue-400 border-blue-500/10',
                        };
                        $tipoLabel = match($l->tipo_lesion) {
                            'lesion' => 'Lesión 🤕',
                            'enfermedad' => 'Enfermedad 🤢',
                            'control' => 'Control 🩺',
                            default => $l->tipo_lesion
                        };
                    @endphp
                    <div class="group flex flex-col gap-3 rounded-xl border border-gray-100 bg-gray-50/50 p-4 transition-all hover:shadow-sm dark:border-white/5 dark:bg-white/5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                {{-- Avatar con iniciales --}}
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border font-black text-sm uppercase {{ $avatarColor }}">
                                    {{ $l->usuario ? collect(explode(' ', $l->usuario->name))->map(fn ($n) => mb_substr($n, 0, 1))->take(2)->join('') : '?' }}
                                </div>
                                {{-- Información del jugador --}}
                                <div class="flex flex-col min-w-0">
                                    <span class="text-sm font-black text-gray-900 dark:text-white truncate">
                                        {{ $l->usuario?->name ?? 'Desconocido' }}
                                    </span>
                                    <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-0.5">
                                        📅 {{ $inicio }} - {{ $fin }}
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Etiquetas --}}
                            <div class="flex flex-col items-end gap-1.5 shrink-0">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ $gravedadColor }}">
                                    {{ $l->gravedad }}
                                </span>
                                <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400">
                                    {{ $tipoLabel }}
                                </span>
                            </div>
                        </div>

                        {{-- Diagnóstico/Descripción --}}
                        <div class="mt-1 rounded-lg bg-white/50 p-3 text-xs text-gray-600 dark:bg-black/20 dark:text-gray-300 border dark:border-white/5">
                            <span class="font-bold text-[10px] uppercase text-gray-400 tracking-wider block mb-1">Diagnóstico / Descripción:</span>
                            {{ $l->descripcion }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
