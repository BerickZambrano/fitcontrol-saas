<x-layouts.auth>
    <div class="flex flex-col gap-8">
        <x-auth-header :title="__('Bienvenido')" :description="__('Ingresa tus credenciales para acceder a tu panel')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Correo Electrónico')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="tu@correo.com"
                class="bg-neutral-50 dark:bg-neutral-900 border-neutral-200 dark:border-neutral-800"
            />

            <!-- Password -->
            <div class="relative flex flex-col gap-2">
                <flux:input
                    name="password"
                    :label="__('Contraseña')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Tu contraseña segura')"
                    viewable
                    class="bg-neutral-50 dark:bg-neutral-900 border-neutral-200 dark:border-neutral-800"
                />

                @if (Route::has('password.request'))
                    <flux:link class="mt-1 text-xs text-neutral-500 hover:text-indigo-600 dark:text-neutral-400 dark:hover:text-indigo-400 self-end transition-colors" :href="route('password.request')" wire:navigate>
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <flux:checkbox name="remember" :label="__('Mantener sesión iniciada')" :checked="old('remember')" class="text-neutral-600 dark:text-neutral-400" />
            </div>

            <div class="mt-2">
                <flux:button variant="primary" type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 py-3 text-lg font-bold shadow-lg shadow-indigo-200 dark:shadow-none transition-all active:scale-95" data-test="login-button">
                    {{ __('Iniciar Sesión') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="pt-4 border-t border-neutral-100 dark:border-neutral-900 text-sm text-center text-neutral-600 dark:text-neutral-400">
                <span>{{ __('¿No tienes una cuenta?') }}</span>
                <flux:link class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline" :href="route('onboarding.index')">{{ __('Solicita acceso') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts.auth>
