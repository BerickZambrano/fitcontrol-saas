<div class="fi-wi-mis-notificaciones">
    @php
        $notificaciones = $this->getNotificaciones();
    @endphp

    <div class="rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-2xl">🔔</span>
            <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">
                Notificaciones
            </h3>
        </div>

        @if(count($notificaciones) > 0)
            <div class="flex flex-col gap-3">
                @foreach($notificaciones as $notif)
                    @php
                        $isUnread = !$notif['leida'];
                        $tipo = $notif['tipo'] ?? 'general';
                        $icon = match($tipo) {
                            'entrenamiento' => '🏋️',
                            'partido' => '⚽',
                            default => '📢',
                        };
                    @endphp
                    <div class="flex items-start gap-3 rounded-xl bg-gray-50 p-4 dark:bg-white/5 {{ $isUnread ? 'border-l-4 border-blue-500' : '' }}">
                        <span class="text-lg flex-shrink-0">{{ $icon }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ $notif['titulo'] ?? '' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $notif['mensaje'] ?? '' }}
                            </p>
                        </div>
                        <span class="text-xs text-gray-400 flex-shrink-0">
                            {{ \Carbon\Carbon::parse($notif['created_at'])->diffForHumans() }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-lg font-bold text-gray-400 dark:text-gray-500">
                    Sin notificaciones
                </p>
            </div>
        @endif
    </div>
</div>
