<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado del Pago - FitControl</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at 50% 0%, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
        }
        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 text-white">

    <div class="glass-panel max-w-lg w-full rounded-3xl p-8 shadow-2xl relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-amber-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl"></div>

        <div class="text-center relative z-10">
            <div class="mx-auto w-20 h-20 bg-gradient-to-tr from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg mb-6 shadow-amber-500/30">
                <!-- Clock / Pending Icon -->
                <svg class="w-10 h-10 text-white animate-pulse" style="width: 40px; height: 40px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <h1 class="text-3xl font-bold mb-3 tracking-tight">Pago en Proceso</h1>
            <p class="text-slate-300 mb-8 text-sm leading-relaxed">
                Wompi está procesando el pago para el club <strong>{{ $tenant->nombre }}</strong>. Esto suele tardar unos minutos si usaste transferencias bancarias o PSE.
            </p>

            <div class="bg-slate-800/50 rounded-2xl p-5 mb-8 border border-slate-700 text-left">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-slate-400 text-sm">Transacción ID</span>
                    <span class="text-white font-mono text-xs">{{ $transactionId }}</span>
                </div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-slate-400 text-sm">Estado actual</span>
                    <span class="text-amber-400 flex items-center font-medium">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400 mr-2 animate-ping"></span>Pendiente de aprobación
                    </span>
                </div>
                <div class="border-t border-slate-700/50 my-3"></div>
                <p class="text-xs text-slate-400 leading-normal">
                    Una vez que el pago sea aprobado, tu club se activará automáticamente de forma inmediata.
                </p>
            </div>

            <div class="space-y-4">
                <a href="{{ route('paywall.callback', ['id' => $transactionId]) }}" class="w-full py-3.5 px-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center text-base">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                    Verificar Estado del Pago
                </a>

                @php
                    $panelId = 'entrenador';
                    try {
                        if (function_exists('filament') && filament()->getCurrentPanel()) {
                            $panelId = filament()->getCurrentPanel()->getId();
                        }
                    } catch (\Exception $e) {}
                @endphp

                <form action="{{ route('filament.' . $panelId . '.auth.logout') }}" method="POST" class="inline-block w-full text-center mt-2">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-slate-200 text-sm underline transition duration-150">
                        Cerrar sesión e intentar más tarde
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
