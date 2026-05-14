<x-layouts.auth>
    <div class="flex flex-col gap-8">
        <x-auth-header :title="__('¿Olvidaste tu contraseña?')" :description="__('Ingresa tu correo para recibir un enlace de restablecimiento')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Correo Electrónico')"
                type="email"
                required
                autofocus
                placeholder="tu@correo.com"
                class="bg-neutral-50 dark:bg-neutral-900 border-neutral-200 dark:border-neutral-800 font-medium"
            />

            <flux:button variant="primary" type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 py-3 text-lg font-bold shadow-lg shadow-indigo-200 dark:shadow-none transition-all active:scale-95" data-test="email-password-reset-link-button">
                {{ __('Enviar enlace de recuperación') }}
            </flux:button>
        </form>

        <div class="pt-4 border-t border-neutral-100 dark:border-neutral-900 text-center text-sm text-neutral-500 dark:text-neutral-400">
            <span>{{ __('O bien,') }}</span>
            <flux:link class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline" :href="route('login')" wire:navigate>{{ __('regresa al inicio de sesión') }}</flux:link>
        </div>
    </div>
</x-layouts.auth>
