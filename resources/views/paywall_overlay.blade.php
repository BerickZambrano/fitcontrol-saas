<div id="paywall-overlay" style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    background-color: rgba(15, 23, 42, 0.75);
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    color: #ffffff;
    box-sizing: border-box;
">
    <div style="
        background: rgba(30, 41, 59, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 40px;
        max-width: 500px;
        width: 90%;
        text-align: center;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        position: relative;
    ">
        <!-- SVG Lock -->
        <div style="
            margin: 0 auto 24px auto;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f87171 0%, #dc2626 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);
        ">
            <svg style="width: 40px; height: 40px; color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 12px; letter-spacing: -0.5px; color: #ffffff;">Acceso Bloqueado</h2>
        <p style="color: #cbd5e1; font-size: 14px; line-height: 1.6; margin-bottom: 24px;">
            El club <strong>{{ $tenant->nombre }}</strong> se encuentra pendiente de pago. Para desbloquear el acceso a todas las funcionalidades premium, por favor completa tu pago.
        </p>

        @php
            $wompiPublicKey = config('services.wompi.public_key');
        @endphp

        <!-- Selector de Plan en Overlay -->
        <div style="margin-bottom: 20px; text-align: left;">
            <p style="color: #94a3b8; font-size: 13px; margin-bottom: 8px; font-weight: 500;">Selecciona tu plan de suscripción:</p>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <!-- Plan Mensual -->
                <label style="cursor: pointer; display: block; position: relative;">
                    <input type="radio" name="plan_overlay" value="mensual" class="overlay-plan-radio" checked style="display:none;">
                    <div class="overlay-plan-card selected-overlay-plan" id="overlay-card-mensual" style="
                        padding: 12px;
                        border-radius: 12px;
                        background: rgba(239, 68, 68, 0.1);
                        border: 2px solid #ef4444;
                        transition: all 0.2s ease;
                    ">
                        <span style="display: block; font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; margin-bottom: 2px;">Mensual</span>
                        <span style="display: block; font-size: 15px; font-weight: 700; color: #ffffff;">$70.000 <span style="font-size: 11px; font-weight: 400; color: #94a3b8;">COP</span></span>
                    </div>
                </label>

                <!-- Plan Anual -->
                <label style="cursor: pointer; display: block; position: relative;">
                    <span style="
                        position: absolute;
                        top: -8px;
                        right: 8px;
                        background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
                        color: #ffffff;
                        font-size: 9px;
                        font-weight: 800;
                        padding: 2px 6px;
                        border-radius: 9999px;
                        box-shadow: 0 2px 4px rgba(217, 119, 6, 0.2);
                        z-index: 10;
                    ">-16%</span>
                    <input type="radio" name="plan_overlay" value="anual" class="overlay-plan-radio" style="display:none;">
                    <div class="overlay-plan-card" id="overlay-card-anual" style="
                        padding: 12px;
                        border-radius: 12px;
                        background: rgba(15, 23, 42, 0.2);
                        border: 2px solid rgba(255, 255, 255, 0.05);
                        transition: all 0.2s ease;
                    ">
                        <span style="display: block; font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; margin-bottom: 2px;">Anual</span>
                        <span style="display: block; font-size: 15px; font-weight: 700; color: #ffffff;">$700.000 <span style="font-size: 11px; font-weight: 400; color: #94a3b8;">COP</span></span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Resumen -->
        <div style="
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            text-align: left;
        ">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span style="color: #94a3b8; font-size: 13px;">Suscripción</span>
                <span id="overlay-selected-plan-text" style="font-weight: 600; font-size: 14px; color: #ffffff;">Plan Mensual</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span style="color: #94a3b8; font-size: 13px;">Monto</span>
                <span id="overlay-selected-amount-text" style="font-weight: 700; font-size: 16px; color: #ef4444;">$70.000 COP</span>
            </div>
            <div style="border-top: 1px solid rgba(255,255,255,0.05); margin: 10px 0;"></div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #94a3b8; font-size: 13px;">Estado</span>
                <span style="color: #ef4444; font-size: 13px; font-weight: 600; display: flex; align-items: center;">
                    <span style="
                        width: 8px;
                        height: 8px;
                        border-radius: 50%;
                        background-color: #ef4444;
                        display: inline-block;
                        margin-right: 8px;
                    "></span>Pendiente
                </span>
            </div>
        </div>

        @if($wompiPublicKey)
            <button type="button" id="wompi-overlay-pay-button" style="
                width: 100%;
                padding: 14px;
                background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
                color: #ffffff;
                font-weight: 600;
                font-size: 16px;
                border: none;
                border-radius: 12px;
                cursor: pointer;
                box-shadow: 0 10px 15px -3px rgba(220, 38, 38, 0.3);
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
            ">
                <svg style="width: 20px; height: 20px; margin-right: 8px; display: inline-block;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <span>Pagar con Wompi</span>
            </button>

            <script type="text/javascript" src="https://checkout.wompi.co/widget.js"></script>
            <script>
                (function() {
                    const payBtn = document.getElementById('wompi-overlay-pay-button');
                    
                    // Manejo del selector de planes
                    const radios = document.querySelectorAll('.overlay-plan-radio');
                    radios.forEach(radio => {
                        radio.addEventListener('change', function() {
                            document.querySelectorAll('.overlay-plan-card').forEach(card => {
                                card.style.border = '2px solid rgba(255, 255, 255, 0.05)';
                                card.style.background = 'rgba(15, 23, 42, 0.2)';
                            });
                            
                            const selectedCard = document.getElementById('overlay-card-' + this.value);
                            if (selectedCard) {
                                selectedCard.style.border = '2px solid #ef4444';
                                selectedCard.style.background = 'rgba(239, 68, 68, 0.1)';
                            }

                            document.getElementById('overlay-selected-plan-text').innerText = this.value === 'mensual' ? 'Plan Mensual' : 'Plan Anual';
                            document.getElementById('overlay-selected-amount-text').innerText = this.value === 'mensual' ? '$70.000 COP' : '$700.000 COP';
                        });
                    });

                    payBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        payBtn.disabled = true;
                        payBtn.style.opacity = '0.7';
                        payBtn.querySelector('span').innerText = 'Preparando pago...';

                        const checkedRadio = document.querySelector('.overlay-plan-radio:checked');
                        const selectedPlan = checkedRadio ? checkedRadio.value : 'mensual';

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
                            payBtn.disabled = false;
                            payBtn.style.opacity = '1';
                            payBtn.querySelector('span').innerText = 'Pagar con Wompi';

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
                            alert('Ocurrió un error al preparar el pago con Wompi. Por favor intenta de nuevo.');
                            payBtn.disabled = false;
                            payBtn.style.opacity = '1';
                            payBtn.querySelector('span').innerText = 'Pagar con Wompi';
                        });
                    });
                })();
            </script>
        @else
            <!-- Legacy Simulation Button -->
            <form action="{{ route('paywall.simulate-payment') }}" method="POST">
                @csrf
                <button type="submit" style="
                    width: 100%;
                    padding: 14px;
                    background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
                    color: #ffffff;
                    font-weight: 600;
                    font-size: 16px;
                    border: none;
                    border-radius: 12px;
                    cursor: pointer;
                    box-shadow: 0 10px 15px -3px rgba(220, 38, 38, 0.3);
                    transition: all 0.2s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <svg style="width: 20px; height: 20px; margin-right: 8px; display: inline-block;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Simular Pago y Desbloquear
                </button>
            </form>
        @endif

        <form id="logout-form-overlay" action="{{ filament()->getLogoutUrl() }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-overlay').submit();" style="
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 14px;
            cursor: pointer;
            text-decoration: underline;
            margin-top: 20px;
            display: inline-block;
        ">
            Cerrar sesión
        </a>
    </div>
</div>
