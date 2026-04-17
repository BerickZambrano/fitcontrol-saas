<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada | FitControl</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.ico') }}">
    @vite(['resources/css/app.css'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0d7cd2 0%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-container {
            text-align: center;
            padding: 3rem;
            max-width: 600px;
        }

        .error-code {
            font-size: 10rem;
            font-weight: 800;
            color: rgba(255, 255, 255, 0.15);
            line-height: 1;
            margin-bottom: -1rem;
            position: relative;
        }

        .error-code::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .error-title {
            font-size: 2rem;
            font-weight: 600;
            color: white;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .error-message {
            font-size: 1.125rem;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .error-icon {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .error-icon svg {
            width: 60px;
            height: 60px;
            color: rgba(255, 255, 255, 0.9);
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            background: white;
            color: #0d7cd2;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 0.75rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background: #f8fafc;
        }

        .btn-home svg {
            width: 20px;
            height: 20px;
        }

        .decorative-dots {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .dot {
            position: absolute;
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
        }

        .dot:nth-child(1) { top: 10%; left: 10%; }
        .dot:nth-child(2) { top: 20%; right: 15%; width: 12px; height: 12px; }
        .dot:nth-child(3) { bottom: 25%; left: 20%; width: 6px; height: 6px; }
        .dot:nth-child(4) { bottom: 15%; right: 25%; width: 10px; height: 10px; }
        .dot:nth-child(5) { top: 40%; left: 5%; width: 6px; height: 6px; }
        .dot:nth-child(6) { top: 60%; right: 8%; width: 8px; height: 8px; }

        @media (max-width: 640px) {
            .error-code {
                font-size: 7rem;
            }
            .error-title {
                font-size: 1.5rem;
            }
            .error-message {
                font-size: 1rem;
            }
            .error-container {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="decorative-dots">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>

    <div class="error-container">
        <div class="error-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.072-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
            </svg>
        </div>

        <div class="error-code">404</div>

        <h1 class="error-title">Página no encontrada</h1>

        <p class="error-message">
            Lo sentimos, la página que estás buscando no existe o ha sido movida.
            Pero no te preocupes, puedes volver al inicio y seguir usando FitControl.
        </p>

        <a href="{{ url('/admin') }}" class="btn-home">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            Volver al inicio
        </a>
    </div>
</body>
</html>
