<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada | FitControl</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }

        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</head>
<body class="bg-slate-50 antialiased min-h-screen flex flex-col justify-center items-center overflow-hidden relative">
    
    <!-- Background decorative blurs -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-cyan-300 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 text-center px-6 md:px-12 w-full max-w-4xl mx-auto">
        
        <div class="mb-10 flex justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="FitControl" class="h-14 md:h-16 w-auto object-contain drop-shadow-sm">
        </div>

        <div class="relative inline-block mb-4">
            <h1 class="text-[8rem] md:text-[12rem] font-extrabold text-transparent bg-clip-text bg-gradient-to-br from-blue-700 to-cyan-500 leading-none tracking-tighter select-none drop-shadow-sm">
                404
            </h1>
        </div>
        
        <h2 class="mt-2 text-3xl md:text-5xl font-bold text-slate-900 tracking-tight">
            ¡Fuera de lugar!
        </h2>
        
        <p class="mt-6 text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
            Parece que te has salido del campo de juego. La página que buscas no existe, ha sido movida o ya no está disponible.
        </p>

        <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ url('/') }}" class="group relative inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-white transition-all duration-300 bg-blue-600 border border-transparent rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 w-full sm:w-auto">
                <svg class="w-5 h-5 mr-2 -ml-1 transition-transform group-hover:-translate-x-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver a la cancha
            </a>
            
            <a href="javascript:history.back()" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-slate-700 transition-all duration-300 bg-white border border-slate-200 rounded-2xl shadow-sm hover:bg-slate-50 hover:text-slate-900 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-200 w-full sm:w-auto">
                Regresar a la anterior
            </a>
        </div>
    </div>
    
    <div class="absolute bottom-8 w-full text-center text-sm text-slate-400 font-medium z-10 tracking-wide">
        &copy; {{ date('Y') }} FitControl. Todos los derechos reservados.
    </div>

</body>
</html>
