<x-filament-widgets::widget>
    @php
        $player = $this->getPlayerData();
        $user = $this->getUser();
    @endphp

    <div class="flex justify-center">
        <div class="relative w-72 h-96 bg-gradient-to-b from-[var(--primary-600)] via-[var(--primary-500)] to-[var(--primary-800)] rounded-2xl shadow-2xl overflow-hidden border-4 border-[var(--primary-900)] transform hover:scale-105 transition-transform duration-300 group">
            <!-- Glow Effect -->
            <div class="absolute inset-0 bg-white opacity-5 group-hover:opacity-15 transition-opacity duration-300"></div>

            <!-- Header: Club Logo & Name -->
            <div class="absolute top-2 left-2 right-2 flex justify-between items-center z-10">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md border border-[var(--primary-900)]">
                    <span class="text-[10px] font-bold text-[var(--primary-900)]">{{ substr($user->tenant?->nombre ?? 'FC', 0, 3) }}</span>
                </div>
                <div class="bg-black/80 px-3 py-0.5 rounded-full border border-[var(--primary-500)]">
                    <span class="text-[10px] font-black text-[var(--primary-400)] uppercase tracking-widest">FITCONTROL </span>
                </div>
            </div>

            <!-- Player Photo Area -->
            <div class="absolute top-12 left-4 right-4 h-48 bg-gray-200 rounded-lg overflow-hidden border-2 border-[var(--primary-900)] shadow-inner flex items-center justify-center">
                @if($user->profile_photo_url)
                    <img src="{{ $user->profile_photo_url }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-500 flex items-center justify-center">
                        <span class="text-6xl font-black text-white/50">{{ $user->initials() }}</span>
                    </div>
                @endif
            </div>

            <!-- Dorsal / Number -->
            <div class="absolute top-14 right-6 bg-[var(--primary-600)] w-12 h-12 rounded-full border-2 border-white flex items-center justify-center shadow-lg z-20">
                <span class="text-2xl font-black text-white">{{ $player?->dorsal ?? '00' }}</span>
            </div>

            <!-- Name & Position -->
            <div class="absolute bottom-16 left-0 right-0 text-center px-2">
                <h3 class="text-xl font-black text-white uppercase tracking-tighter truncate drop-shadow-lg">
                    {{ $user->name }}
                </h3>
                <div class="inline-block bg-white text-[var(--primary-700)] px-4 py-0.5 rounded-sm text-[10px] font-bold uppercase tracking-widest mt-1 shadow-sm">
                    {{ $player?->posicion ?? 'SIN POSICIÓN' }}
                </div>
            </div>

            <!-- Stats Footer -->
            <div class="absolute bottom-2 left-2 right-2 h-12 bg-black/90 rounded-xl border border-[var(--primary-600)] flex items-center justify-around px-2">
                <div class="text-center">
                    <p class="text-[8px] text-[var(--primary-400)] uppercase font-bold">ALT</p>
                    <p class="text-sm font-black text-white leading-none">{{ $player?->altura ?? '--' }} <span class="text-[8px]">cm</span></p>
                </div>
                <div class="w-px h-6 bg-[var(--primary-900)]"></div>
                <div class="text-center">
                    <p class="text-[8px] text-[var(--primary-400)] uppercase font-bold">PESO</p>
                    <p class="text-sm font-black text-white leading-none">{{ $player?->peso ?? '--' }} <span class="text-[8px]">kg</span></p>
                </div>
                <div class="w-px h-6 bg-[var(--primary-900)]"></div>
                <div class="text-center">
                    <p class="text-[8px] text-[var(--primary-400)] uppercase font-bold">PIE</p>
                    <p class="text-sm font-black text-white leading-none uppercase">{{ substr($player?->pierna_habil ?? '--', 0, 3) }}</p>
                </div>
            </div>

            <!-- Texture/Holographic Pattern Overlay -->
            <div class="absolute inset-0 pointer-events-none opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 10px 10px;"></div>
        </div>
    </div>
</x-filament-widgets::widget>
