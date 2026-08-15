@props([
    'title' => 'Belum ada data',
    'description' => null,
])

<div {{ $attributes->class('flex flex-col items-center justify-center gap-3 px-6 py-12 text-center') }}>
    <div class="rounded-full bg-gray-100 p-3 text-gray-400 dark:bg-gray-700 dark:text-gray-500">
        <svg class="h-6 w-6" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
        </svg>
    </div>

    <div>
        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $title }}</p>

        @if ($description)
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
        @endif
    </div>

    @if ($slot->isNotEmpty())
        <div class="mt-2">{{ $slot }}</div>
    @endif
</div>
