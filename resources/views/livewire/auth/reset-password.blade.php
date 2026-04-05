<x-layouts.auth>
    <div class="flex flex-col gap-8">
        <x-auth-header :title="__('Restablecer contraseña')" :description="__('Por favor, ingresa tu nueva contraseña a continuación')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <flux:input
                name="email"
                value="{{ request('email') }}"
                :label="__('Correo Electrónico')"
                type="email"
                required
                autocomplete="email"
                class="bg-neutral-50 dark:bg-neutral-900 border-neutral-200 dark:border-neutral-800 font-medium"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Nueva Contraseña')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Tu nueva contraseña segura')"
                viewable
                class="bg-neutral-50 dark:bg-neutral-900 border-neutral-200 dark:border-neutral-800 font-medium"
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirmar nueva contraseña')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Repite tu nueva contraseña')"
                viewable
                class="bg-neutral-50 dark:bg-neutral-900 border-neutral-200 dark:border-neutral-800 font-medium"
            />

            <div class="mt-4">
                <flux:button type="submit" variant="primary" class="w-full bg-indigo-600 hover:bg-indigo-700 py-3 text-lg font-bold shadow-lg shadow-indigo-200 dark:shadow-none transition-all active:scale-95" data-test="reset-password-button">
                    {{ __('Restablecer contraseña') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.auth>
