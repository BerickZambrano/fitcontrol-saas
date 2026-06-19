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

            <!-- Selector de Plan -->
            <div class="mb-8 text-left">
                <p class="text-slate-400 text-sm mb-3 font-medium">Selecciona tu plan de suscripción:</p>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Tarjeta Plan Mensual -->
                    <label class="cursor-pointer">
                        <input type="radio" name="plan" value="mensual" class="sr-only peer" checked>
                        <div class="p-4 rounded-2xl bg-slate-800/40 border border-slate-700 peer-checked:border-amber-500 peer-checked:bg-amber-500/10 hover:bg-slate-800/60 transition-all duration-200">
                            <span class="block text-xs font-semibold text-slate-400 tracking-wider uppercase mb-1">Mensual</span>
                            <span class="block text-lg font-bold text-white leading-none">$70.000 <span class="text-xs font-normal text-slate-400">COP</span></span>
                        </div>
                    </label>

                    <!-- Tarjeta Plan Anual -->
                    <label class="cursor-pointer relative">
                        <div class="absolute -top-2 right-2 bg-gradient-to-r from-amber-400 to-amber-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow shadow-amber-500/30">
                            -16%
                        </div>
                        <input type="radio" name="plan" value="anual" class="sr-only peer">
                        <div class="p-4 rounded-2xl bg-slate-800/40 border border-slate-700 peer-checked:border-amber-500 peer-checked:bg-amber-500/10 hover:bg-slate-800/60 transition-all duration-200">
                            <span class="block text-xs font-semibold text-slate-400 tracking-wider uppercase mb-1">Anual</span>
                            <span class="block text-lg font-bold text-white leading-none">$700.000 <span class="text-xs font-normal text-slate-400">COP</span></span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Resumen y Estado -->
            <div class="bg-slate-800/50 rounded-2xl p-5 mb-8 border border-slate-700 text-left">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-slate-400 text-sm">Suscripción</span>
                    <span id="selected-plan-text" class="text-white font-semibold">Plan Mensual</span>
                </div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-slate-400 text-sm">Monto a pagar</span>
                    <span id="selected-amount-text" class="text-amber-400 font-bold text-lg">$70.000 COP</span>
                </div>
                <div class="border-t border-slate-700/50 my-3"></div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-400">Estado</span>
                    <span class="text-amber-400 flex items-center font-medium">
                        <span class="w-2 h-2 rounded-full bg-amber-400 mr-2 animate-ping"></span>Pendiente
                    </span>
                </div>
            </div>

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/20 text-red-200 text-sm rounded-xl p-3 mb-6 text-left">
                    {{ session('error') }}
                </div>
            @endif

            @if($publicKey)
                <button type="button" id="wompi-pay-button" class="w-full py-3.5 px-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center text-base">
                    <svg class="w-5 h-5 mr-2" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Pagar con Wompi
                </button>
                
                <script type="text/javascript" src="https://checkout.wompi.co/widget.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const planRadios = document.querySelectorAll('input[name="plan"]');
                        const planText = document.getElementById('selected-plan-text');
                        const amountText = document.getElementById('selected-amount-text');
                        const payBtn = document.getElementById('wompi-pay-button');

                        const planNames = {
                            mensual: 'Plan Mensual',
                            anual: 'Plan Anual'
                        };

                        const planPrices = {
                            mensual: '$70.000 COP',
                            anual: '$700.000 COP'
                        };

                        function updatePlanDisplay() {
                            const checkedPlan = document.querySelector('input[name="plan"]:checked').value;
                            planText.textContent = planNames[checkedPlan];
                            amountText.textContent = planPrices[checkedPlan];
                        }

                        planRadios.forEach(radio => {
                            radio.addEventListener('change', updatePlanDisplay);
                        });

                        payBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            
                            // Deshabilitar botón durante carga
                            payBtn.disabled = true;
                            payBtn.innerHTML = `
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" style="width: 20px; height: 20px; display: inline-block;" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Preparando pago...
                            `;

                            const selectedPlan = document.querySelector('input[name="plan"]:checked').value;

                            fetch("{{ route('paywall.prepare') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({ plan: selectedPlan })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Error al preparar el pago.');
                                }
                                return response.json();
                            })
                            .then(data => {
                                // Restaurar botón
                                payBtn.disabled = false;
                                payBtn.innerHTML = `
                                        <svg class="w-5 h-5 mr-2" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        Pagar con Wompi
                                    `;

                                const checkoutOptions = {
                                    currency: data.currency,
                                    amountInCents: data.amountInCents,
                                    reference: data.reference,
                                    publicKey: data.publicKey,
                                    redirectUrl: "{{ route('paywall.callback') }}"
                                };

                                if (data.signature) {
                                    checkoutOptions.signature = {
                                        integrity: data.signature
                                    };
                                }

                                const checkout = new WidgetCheckout(checkoutOptions);

                                checkout.open(function(result) {
                                    const transaction = result.transaction;
                                    if (transaction) {
                                        window.location.href = "{{ route('paywall.callback') }}?id=" + transaction.id;
                                    }
                                });
                            })
                            .catch(error => {
                                console.error(error);
                                alert('Ocurrió un error al iniciar el pago con Wompi. Por favor intenta de nuevo.');
                                
                                // Restaurar botón
                                payBtn.disabled = false;
                                payBtn.innerHTML = `
                                    <svg class="w-5 h-5 mr-2" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    Pagar con Wompi
                                `;
                            });
                        });
                    });
                </script>
            @else
                <!-- Legacy Simulation Button (Fallback si no hay keys de Wompi) -->
                <form action="{{ route('paywall.simulate-payment') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Simular Pago y Desbloquear
                    </button>
                </form>
            @endif
        </div>
    </div>

</body>
</html>
