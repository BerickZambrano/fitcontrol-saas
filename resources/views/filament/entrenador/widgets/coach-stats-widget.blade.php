<x-filament-widgets::widget>
    @php
        $data = $this->getCoachData();
        $user = $this->getUser();
    @endphp

    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 dark:from-emerald-950 dark:via-teal-950 dark:to-emerald-900 rounded-2xl shadow-xl p-6 text-white border border-emerald-500/20">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-black tracking-tight">¡Hola, Entrenador {{ $user->name }}! 👋</h2>
                <p class="text-emerald-100 dark:text-emerald-300 mt-2 text-sm md:text-base max-w-xl">
                    Bienvenido a tu panel de control. Desde aquí podrás gestionar tus equipos asignados, evaluar el rendimiento físico y técnico de tus jugadores, y controlar las asistencias a entrenamientos.
                </p>
            </div>

            <!-- Stats grid -->
            <div class="grid grid-cols-2 gap-4 w-full md:w-auto min-w-[280px]">
                <div class="bg-white/10 dark:bg-black/20 backdrop-blur-md rounded-xl p-4 border border-white/10 flex flex-col items-center justify-center text-center">
                    <span class="text-3xl font-black tracking-tighter">{{ $data['totalEquipos'] }}</span>
                    <span class="text-xs font-semibold text-emerald-200 dark:text-emerald-300 uppercase tracking-widest mt-1">Equipos a cargo</span>
                </div>
                <div class="bg-white/10 dark:bg-black/20 backdrop-blur-md rounded-xl p-4 border border-white/10 flex flex-col items-center justify-center text-center">
                    <span class="text-3xl font-black tracking-tighter">{{ $data['totalJugadores'] }}</span>
                    <span class="text-xs font-semibold text-emerald-200 dark:text-emerald-300 uppercase tracking-widest mt-1">Jugadores</span>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
