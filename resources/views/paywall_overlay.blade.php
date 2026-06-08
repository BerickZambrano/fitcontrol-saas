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

        <div style="
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            text-align: left;
        ">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="color: #94a3b8; font-size: 14px;">Plan Premium</span>
                <span style="font-weight: 600; font-size: 16px; color: #ffffff;">$99.00 USD</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #94a3b8; font-size: 14px;">Estado</span>
                <span style="color: #ef4444; font-size: 14px; font-weight: 600; display: flex; align-items: center;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #ef4444; display: inline-block; margin-right: 8px;"></span>Pendiente
                </span>
            </div>
        </div>

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

        @php
            $panelId = 'entrenador';
            try {
                if (function_exists('filament') && filament()->getCurrentPanel()) {
                    $panelId = filament()->getCurrentPanel()->getId();
                }
            } catch (\Exception $e) {}
        @endphp

        <form action="{{ route('filament.' . $panelId . '.auth.logout') }}" method="POST" style="margin-top: 20px;">
            @csrf
            <button type="submit" style="
                background: none;
                border: none;
                color: #94a3b8;
                font-size: 14px;
                cursor: pointer;
                text-decoration: underline;
            ">
                Cerrar sesión
            </button>
        </form>
    </div>
</div>
