<x-filament-widgets::widget>
    @php
        $lesionados = $this->getJugadoresNoAptos();
    @endphp

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 flex flex-col gap-4">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3">
            <h3 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
                <span>🏥</span> Jugadores de Baja / No Aptos
            </h3>
            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Historial médico activo</span>
        </div>

        @if($lesionados->isEmpty())
            <div class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm">
                Todos los jugadores están aptos y listos para jugar. ¡Buen trabajo! 👍
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                            <th class="py-2.5">Jugador</th>
                            <th class="py-2.5">Tipo</th>
                            <th class="py-2.5">Descripción / Diagnóstico</th>
                            <th class="py-2.5 text-center">Gravedad</th>
                            <th class="py-2.5 text-right">Inactividad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lesionados as $l)
                            @php
                                $inicio = \Carbon\Carbon::parse($l->fecha_inicio)->format('d/m/Y');
                                $fin = $l->fecha_fin ? \Carbon\Carbon::parse($l->fecha_fin)->format('d/m/Y') : 'Indefinido';
                            @endphp
                            <tr class="border-b border-gray-50 dark:border-gray-800/50 last:border-b-0 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="py-3 text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $l->usuario?->name ?? 'Desconocido' }}
                                </td>
                                <td class="py-3 text-xs capitalize text-gray-600 dark:text-gray-300 font-medium">
                                    {{ match($l->tipo_lesion) {
                                        'lesion' => 'Lesión 🤕',
                                        'enfermedad' => 'Enfermedad 🤢',
                                        'control' => 'Control 🩺',
                                        default => $l->tipo_lesion
                                    } }}
                                </td>
                                <td class="py-3 text-xs text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                    {{ $l->descripcion }}
                                </td>
                                <td class="py-3 text-center">
                                    <span class="text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider
                                        @if($l->gravedad === 'grave') bg-red-100 text-red-800 dark:bg-red-950/50 dark:text-red-300
                                        @elseif($l->gravedad === 'media') bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:text-amber-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 @endif">
                                        {{ $l->gravedad }}
                                    </span>
                                </td>
                                <td class="py-3 text-xs text-right font-medium text-gray-600 dark:text-gray-400">
                                    {{ $inicio }} - {{ $fin }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-filament-widgets::widget>
