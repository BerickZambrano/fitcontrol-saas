<x-filament-panels::page>
    {{-- Tabs --}}
    <div class="flex gap-2 mb-6 overflow-x-auto">
        @foreach($this->getTabs() as $key => $label)
            <button
                wire:click="$set('activeTab', '{{ $key }}')"
                class="px-5 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wide transition-all flex-shrink-0
                    {{ $activeTab === $key
                        ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/25'
                        : 'bg-gray-100 text-gray-500 hover:bg-gray-200 dark:bg-white/5 dark:text-gray-400 dark:hover:bg-white/10' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Partidos Tab --}}
    @if($activeTab === 'partidos')
        <div class="flex flex-col gap-3">
            @forelse($this->getPartidos() as $p)
                <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-base font-bold text-gray-900 dark:text-white">{{ $p['partido'] }}</p>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $p['fecha'] !== '—' ? \Carbon\Carbon::parse($p['fecha'])->format('d/m/Y') : '—' }}</span>
                    </div>
                    <div class="flex gap-6 text-sm">
                        <span class="text-green-600 font-bold">⚽ {{ $p['goles'] }}</span>
                        <span class="text-blue-600 font-bold">🎯 {{ $p['asistencias'] }}</span>
                        <span class="text-gray-600 dark:text-gray-400 font-bold">⏱️ {{ $p['minutos'] }}'</span>
                        @if($p['amarillas'] > 0)
                            <span class="text-yellow-600 font-bold">🟨 {{ $p['amarillas'] }}</span>
                        @endif
                        @if($p['rojas'] > 0)
                            <span class="text-red-600 font-bold">🟥 {{ $p['rojas'] }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-xl border border-gray-200 dark:border-white/10 dark:bg-white/5">
                    <p class="text-lg font-bold text-gray-400">Sin partidos registrados</p>
                </div>
            @endforelse
        </div>
    @endif

    {{-- Pagos Tab --}}
    @if($activeTab === 'pagos')
        <div class="flex flex-col gap-3">
            @forelse($this->getPagos() as $p)
                <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-white/10 dark:bg-white/5 flex items-center justify-between">
                    <div>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            ${{ number_format($p['monto'], 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ $p['fecha'] !== '—' ? \Carbon\Carbon::parse($p['fecha'])->format('d/m/Y') : '—' }}
                        </p>
                    </div>
                    <span class="px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide
                        {{ $p['estado'] === 'pagado' ? 'bg-green-100 text-green-700' : ($p['estado'] === 'pendiente' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                        {{ $p['estado'] }}
                    </span>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-xl border border-gray-200 dark:border-white/10 dark:bg-white/5">
                    <p class="text-lg font-bold text-gray-400">Sin pagos registrados</p>
                </div>
            @endforelse
        </div>
    @endif

    {{-- Médico Tab --}}
    @if($activeTab === 'medico')
        <div class="flex flex-col gap-3">
            @forelse($this->getMedico() as $m)
                <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-base font-bold text-gray-900 dark:text-white">{{ $m['tipo'] }}</p>
                        @if($m['apto'] !== null)
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide
                                {{ $m['apto'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $m['apto'] ? 'Apto' : 'No apto' }}
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $m['descripcion'] }}</p>
                    <p class="text-xs text-gray-400 mt-2">
                        {{ $m['fecha_inicio'] !== '—' ? \Carbon\Carbon::parse($m['fecha_inicio'])->format('d/m/Y') : '—' }}
                        → {{ $m['fecha_fin'] }}
                        @if($m['gravedad'] !== '—') · {{ $m['gravedad'] }} @endif
                    </p>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-xl border border-gray-200 dark:border-white/10 dark:bg-white/5">
                    <p class="text-lg font-bold text-gray-400">Sin registros médicos</p>
                </div>
            @endforelse
        </div>
    @endif
</x-filament-panels::page>
