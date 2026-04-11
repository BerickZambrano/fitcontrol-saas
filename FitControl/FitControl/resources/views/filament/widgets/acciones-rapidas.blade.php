<div class="fi-wi-acciones-rapidas">
    <div class="flex flex-col md:flex-row gap-6">
        @foreach ($this->getActions() as $action)
            <a
                href="{{ $action['url'] }}"
                class="group flex flex-row items-center gap-5 rounded-2xl border-2 border-gray-200 bg-white px-6 py-5 shadow-sm transition-all hover:border-gray-300 hover:shadow-lg dark:border-white/10 dark:bg-white/5 dark:hover:border-white/20"
                style="flex: 1 1 0; min-width: 0;"
            >
                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-{{ $action['color'] }}-100 text-{{ $action['color'] }}-600 transition-colors group-hover:bg-{{ $action['color'] }}-200 group-hover:scale-110 dark:bg-{{ $action['color'] }}-900/30 dark:text-{{ $action['color'] }}-400">
                    <x-filament::icon
                        :icon="$action['icon']"
                        class="h-7 w-7"
                    />
                </div>
                <span class="text-lg font-bold text-gray-700 group-hover:text-gray-900 dark:text-gray-200 dark:group-hover:text-white whitespace-nowrap overflow-hidden text-ellipsis">
                    {{ $action['label'] }}
                </span>
            </a>
        @endforeach
    </div>
</div>
