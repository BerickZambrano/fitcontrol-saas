<div class="fi-wi-acciones-rapidas">
    <div class="flex flex-wrap gap-4">
        @foreach ($this->getActions() as $action)
            <a
                href="{{ $action['url'] }}"
                class="flex-1 min-w-[200px] group flex flex-row items-center gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 shadow-sm transition-all hover:border-blue-500 hover:shadow-md dark:border-white/10 dark:bg-white/5 dark:hover:border-blue-500"
            >
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-{{ $action['color'] }}-100 text-{{ $action['color'] }}-600 transition-colors group-hover:bg-{{ $action['color'] }}-200 dark:bg-{{ $action['color'] }}-900/30 dark:text-{{ $action['color'] }}-400">
                    <x-filament::icon
                        :icon="$action['icon']"
                        class="h-6 w-6"
                    />
                </div>
                <span class="text-sm font-bold text-gray-700 group-hover:text-blue-600 dark:text-gray-200 dark:group-hover:text-blue-400 truncate tracking-tight">
                    {{ $action['label'] }}
                </span>
            </a>
        @endforeach
    </div>
</div>
