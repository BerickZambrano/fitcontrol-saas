<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suscripción Requerida - FitControl</title>
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
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 text-white">

    <div class="glass-panel max-w-lg w-full rounded-3xl p-8 shadow-2xl relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-amber-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl"></div>

        <div class="text-center relative z-10">
            <div class="mx-auto w-20 h-20 bg-gradient-to-tr from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg mb-6 shadow-amber-500/30 animate-pulse-slow">
                <svg class="w-10 h-10 text-white" style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>

            <h1 class="text-3xl font-bold mb-3 tracking-tight">Acceso Bloqueado</h1>
            <p class="text-slate-300 mb-8 text-sm leading-relaxed">
                El club <strong>{{ $tenant->nombre }}</strong> se encuentra pendiente de pago. Para desbloquear el acceso a todas las funcionalidades premium, por favor completa tu pago.
            </p>

            <div class="bg-slate-800/50 rounded-xl p-5 mb-8 border border-slate-700">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-slate-400 text-sm">Plan Premium</span>
                    <span class="text-white font-semibold">$99.00 USD</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-400">Estado</span>
                    <span class="text-amber-400 flex items-center font-medium">
                        <span class="w-2 h-2 rounded-full bg-amber-400 mr-2"></span>Pendiente
                    </span>
                </div>
            </div>

            <form action="{{ route('paywall.simulate-payment') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Simular Pago y Desbloquear
                </button>
            </form>
        </div>
    </div>

</body>
</html>
