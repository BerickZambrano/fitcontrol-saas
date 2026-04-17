<div class="flex flex-col gap-6">
    <div class="flex flex-col gap-2 text-center md:text-left">
        <h2 class="text-xl font-semibold tracking-tight text-neutral-900 dark:text-white">Verificación de Seguridad</h2>
        <p class="text-sm text-neutral-500 dark:text-neutral-400">
            Hemos enviado un código de 6 dígitos a tu correo electrónico. Por favor, ingrésalo a continuación para continuar.
        </p>
    </div>

    <form wire:submit="verify" class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <flux:input 
                wire:model="code" 
                label="Código de Seguridad" 
                placeholder="000000" 
                maxlength="6"
                required
                autofocus
            />
            
            @if ($error)
                <p class="text-sm font-medium text-red-500">{{ $error }}</p>
            @endif
        </div>

        <flux:button type="submit" variant="primary" class="w-full" wire:loading.attr="disabled">
            <span wire:loading.remove>Verificar y Continuar</span>
            <span wire:loading>Verificando...</span>
        </flux:button>
    </form>

    <div class="text-center">
        <p class="text-sm text-neutral-500 dark:text-neutral-400">
            ¿No recibiste el código? 
            <button type="button" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400" onclick="window.location.reload()">
                Volver a intentar
            </button>
        </p>
    </div>
</div>
