@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center md:text-left">
    <flux:heading size="xl" class="font-black tracking-tight text-neutral-900 dark:text-white">{{ $title }}</flux:heading>
    <flux:subheading class="mt-2 text-neutral-500 dark:text-neutral-400">{{ $description }}</flux:subheading>
</div>
