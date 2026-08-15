@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => false,
])

@php
    $variants = [
        'info'    => 'text-blue-800 bg-blue-50 dark:bg-gray-800 dark:text-blue-400',
        'success' => 'text-green-800 bg-green-50 dark:bg-gray-800 dark:text-green-400',
        'warning' => 'text-yellow-800 bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300',
        'danger'  => 'text-red-800 bg-red-50 dark:bg-gray-800 dark:text-red-400',
        'neutral' => 'text-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-gray-300',
    ];

    $id = 'alert-'.uniqid();
@endphp

<div id="{{ $id }}" role="alert"
     {{ $attributes->class(['flex items-start gap-3 rounded-lg p-4 text-sm', $variants[$variant] ?? $variants['info']]) }}>
    <svg class="mt-0.5 h-4 w-4 shrink-0" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
    </svg>

    <div class="flex-1">
        @if ($title)
            <span class="font-medium">{{ $title }}</span>
            <div class="mt-1">{{ $slot }}</div>
        @else
            {{ $slot }}
        @endif
    </div>

    @if ($dismissible)
        <button type="button"
                class="-my-1.5 -me-1.5 ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg p-1.5 hover:bg-black/5 focus:ring-2 focus:ring-gray-300 dark:hover:bg-white/10"
                data-dismiss-target="#{{ $id }}"
                aria-label="Tutup">
            <span class="sr-only">Tutup</span>
            <svg class="h-3 w-3" aria-hidden="true" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    @endif
</div>
