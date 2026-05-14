<x-filament-widgets::widget>
    <div class="fi-wi-acciones-rapidas w-full">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full">
            @foreach ($this->getActions() as $action)
                <a
                    href="{{ $action['url'] }}"
                    class="group flex flex-row items-center gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-all hover:border-primary-500 hover:shadow-md dark:border-white/10 dark:bg-white/5 dark:hover:border-primary-500 w-full"
                >
                    {{-- Icon Container --}}
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-{{ $action['color'] }}-100 text-{{ $action['color'] }}-600 transition-colors group-hover:bg-{{ $action['color'] }}-200 dark:bg-{{ $action['color'] }}-900/30 dark:text-{{ $action['color'] }}-400">
                        <x-filament::icon
                            :icon="$action['icon']"
                            class="h-7 w-7"
                        />
                    </div>

                    {{-- Text --}}
                    <div class="flex flex-col min-w-0">
                        <span class="text-sm font-black text-gray-900 dark:text-white truncate uppercase tracking-tight">
                            {{ $action['label'] }}
                        </span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            Acceso rápido
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
