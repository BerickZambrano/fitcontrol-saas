<x-filament-widgets::widget>
    @php
        $partidos = $this->getPartidos();
    @endphp

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 flex flex-col gap-4">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3">
            <h3 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
                <span>⚽</span> Próximos Partidos
            </h3>
            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Siguientes 5 partidos</span>
        </div>

        @if($partidos->isEmpty())
            <div class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm">
                No hay partidos programados.
            </div>
        @else
            <div class="flex flex-col gap-3">
                @foreach($partidos as $p)
                    @php
                        $fechaFormatted = \Carbon\Carbon::parse($p->fecha)->translatedFormat('d \d\e M, Y');
                        $horaFormatted = \Carbon\Carbon::parse($p->hora)->format('H:i');
                    @endphp
                    <div class="flex flex-col gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors border border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                {{ $p->torneo?->nombre ?? 'Amistoso' }}
                            </span>
                            @if($p->fase)
                                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    {{ match ($p->fase) {
                                        'grupo' => 'Fase de Grupos',
                                        'octavos' => 'Octavos',
                                        'cuartos' => 'Cuartos',
                                        'semifinal' => 'Semifinal',
                                        'final' => 'Final',
                                        'amistoso' => 'Amistoso',
                                        default => $p->fase
                                    } }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $p->local?->nombre ?? 'Por definir' }}</span>
                                <span class="text-xs text-gray-400">vs</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $p->visitante?->nombre ?? 'Por definir' }}</span>
                            </div>
                            <div class="text-right flex flex-col gap-0.5">
                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200">📅 {{ $fechaFormatted }}</span>
                                <span class="text-[11px] text-gray-500 dark:text-gray-400 font-medium">⏰ {{ $horaFormatted }} | 📍 {{ $p->instalacion?->nombre ?? 'Por definir' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-widgets::widget>
