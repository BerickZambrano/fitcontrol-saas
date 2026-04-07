<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-neutral-950 font-sans">
        <div class="flex min-h-screen w-full flex-col md:flex-row">
            <!-- Left Side: Background Image & Branding -->
            <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-neutral-900 p-12 text-white md:flex">
                <!-- Background Image with Overlay -->
                <div class="absolute inset-0 z-0 overflow-hidden">
                    <img src="{{ asset('images/auth-bg.png') }}" alt="FitControl Background" class="h-full w-full object-cover opacity-60 brightness-75 transition-all duration-700 hover:scale-105" />
                    <div class="absolute inset-0 bg-linear-to-t from-neutral-950/80 to-transparent"></div>
                </div>

                <!-- Logo centrado -->
                <div class="relative z-10 flex flex-col items-center gap-8 text-center">
                    <img src="{{ asset('images/logosf.png') }}" alt="FitControl Logo" class="h-48 w-48 object-contain drop-shadow-2xl" />

                    <div>
                        <h2 class="text-3xl font-bold leading-tight tracking-tight">
                            Eleva tu entrenamiento al siguiente nivel con <span class="text-indigo-400">FitControl</span>.
                        </h2>
                        <p class="mt-4 text-lg text-neutral-300">
                            Gestiona tus entrenamientos, sigue tu progreso y alcanza tus objetivos de forma inteligente.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="flex flex-1 flex-col items-center justify-center bg-white p-6 dark:bg-neutral-950 md:p-12 lg:p-24">
                <div class="w-full max-w-sm">
                    <!-- Mobile Branding -->
                    <div class="mb-10 flex flex-col items-center gap-4 text-center md:hidden">
                        <a href="{{ route('home') }}" class="flex flex-col items-center gap-4" wire:navigate>
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-600 shadow-indigo-200 shadow-2xl">
                                <x-app-logo-icon class="h-10 w-10 fill-current text-white" />
                            </div>
                            <span class="text-3xl font-black text-neutral-900 dark:text-white">FitControl</span>
                        </a>
                    </div>

                    <div class="flex flex-col gap-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
