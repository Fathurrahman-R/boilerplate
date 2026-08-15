@props([
    'id' => null,
    'label' => null,
    'placement' => 'bottom',
    'width' => 'w-44',
])

@php
    $id ??= 'dropdown-'.uniqid();
@endphp

<div class="relative inline-block">
    @isset($trigger)
        {{-- Trigger kustom: pemanggil bertanggung jawab atas tampilannya,
             atribut data-dropdown-* ditambahkan di sini. --}}
        <span data-dropdown-toggle="{{ $id }}" data-dropdown-placement="{{ $placement }}" class="cursor-pointer">
            {{ $trigger }}
        </span>
    @else
        <button type="button"
                data-dropdown-toggle="{{ $id }}"
                data-dropdown-placement="{{ $placement }}"
                {{ $attributes->class('inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700') }}>
            {{ $label }}
            <svg class="h-2.5 w-2.5" aria-hidden="true" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
            </svg>
        </button>
    @endisset

    <div id="{{ $id }}"
         class="z-50 hidden {{ $width }} divide-y divide-gray-100 rounded-lg bg-white shadow-sm dark:divide-gray-600 dark:bg-gray-700">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
            {{ $slot }}
        </ul>
    </div>
</div>
