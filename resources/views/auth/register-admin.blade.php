<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Administrador — FitControl</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#172554',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        {{-- Left Side: Branding --}}
        <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-brand-950 via-brand-900 to-brand-800 items-center justify-center overflow-hidden">
            {{-- Background pattern --}}
            <div class="absolute inset-0">
                <div class="absolute top-20 left-20 w-72 h-72 bg-brand-600/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-brand-700/5 rounded-full blur-3xl"></div>
            </div>

            {{-- Branding content --}}
            <div class="relative z-10 p-16 max-w-xl">
                {{-- Logo --}}
                <div class="flex items-center gap-4 mb-16">
                    <div class="w-14 h-14 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-xl shadow-brand-900/30">
                        <img src="{{ asset('images/logo.png') }}" alt="FitControl" class="w-9 h-9 object-contain">
                    </div>
                    <span class="text-white text-3xl font-black tracking-tighter uppercase italic">FitControl</span>
                </div>

                <h2 class="text-5xl font-black text-white leading-tight mb-6">
                    El siguiente nivel
                    <br>
                    <span class="text-blue-400">empieza aquí.</span>
                </h2>
                <p class="text-blue-200/80 text-lg leading-relaxed">
                    Plataforma líder en gestión deportiva. Potencia el rendimiento de tu club desde hoy mismo.
                </p>

                {{-- Steps indicator --}}
                <div class="mt-12 flex items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-400/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-blue-300/60 text-sm font-medium line-through">Registro</span>
                    </div>
                    <div class="w-6 h-px bg-blue-600/40"></div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-400 rounded-lg flex items-center justify-center shadow-lg shadow-blue-400/20">
                            <span class="text-white font-bold text-sm">2</span>
                        </div>
                        <span class="text-white text-sm font-semibold">Crear Admin</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side: Form --}}
        <div class="flex-1 flex items-center justify-center px-6 py-12 bg-white">
            <div class="w-full max-w-md">
                {{-- Mobile Logo --}}
                <div class="lg:hidden flex items-center gap-3 mb-12">
                    <div class="w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center shadow-lg shadow-brand-600/20">
                        <img src="{{ asset('images/logo.png') }}" alt="FitControl" class="w-6 h-6 object-contain">
                    </div>
                    <span class="text-gray-900 text-2xl font-black tracking-tighter uppercase italic">FitControl</span>
                </div>

                {{-- Header --}}
                <div class="mb-10">
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">
                        Crea tu cuenta
                        <span class="text-brand-600">Administrador</span>
                    </h1>
                    <p class="text-gray-500 mt-2 text-base">
                        Configura las credenciales de acceso principal para tu club.
                    </p>
                </div>

                {{-- Tenant info card --}}
                @if(isset($tenant))
                <div class="mb-8 bg-brand-50 border border-brand-100 rounded-2xl px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m12 0v-5a1 1 0 00-1-1H9a1 1 0 00-1 1v5m6 0h2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $tenant->nombre ?? 'Tu Club' }}</p>
                            <p class="text-xs text-gray-500">{{ $tenant->ciudad ?? '' }}, {{ $tenant->pais ?? '' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Form --}}
                <form method="POST" class="space-y-5">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                            Nombre completo
                        </label>
                        <input type="text" name="name" required autofocus
                            value="{{ old('name') }}"
                            placeholder="Ej: Juan Pérez"
                            class="w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 focus:bg-white transition-all placeholder:text-gray-400 placeholder:font-normal placeholder:normal-case">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                            Correo electrónico
                        </label>
                        <input type="email" name="email" required
                            value="{{ old('email') ?? ($tenant->email_corporativo ?? '') }}"
                            placeholder="correo@ejemplo.com"
                            class="w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 focus:bg-white transition-all placeholder:text-gray-400 placeholder:font-normal placeholder:normal-case">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                            Contraseña
                        </label>
                        <input type="password" name="password" required
                            placeholder="Mínimo 8 caracteres"
                            class="w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 focus:bg-white transition-all placeholder:text-gray-400 placeholder:font-normal placeholder:normal-case">
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                            Confirmar contraseña
                        </label>
                        <input type="password" name="password_confirmation" required
                            placeholder="Repite tu contraseña"
                            class="w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 focus:bg-white transition-all placeholder:text-gray-400 placeholder:font-normal placeholder:normal-case">
                    </div>

                    {{-- Errors --}}
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full py-4 rounded-xl bg-brand-600 text-white font-black uppercase tracking-widest text-sm hover:bg-brand-700 transition-all shadow-lg shadow-brand-600/25 active:scale-[0.98] mt-2">
                        Crear Cuenta
                    </button>
                </form>

                {{-- Footer --}}
                <div class="mt-10 pt-6 border-t border-gray-100 text-center">
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <img src="{{ asset('images/logo.png') }}" alt="FitControl" class="w-5 h-5 object-contain">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Plataforma SaaS</span>
                    </div>
                    <p class="text-gray-400 text-xs">
                        &copy; {{ date('Y') }} FitControl. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
