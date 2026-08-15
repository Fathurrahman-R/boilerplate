@props([
    'table' => null,
    'placeholder' => 'Cari…',
])

{{--
    Baris pencarian + filter di atas tabel. Filter tambahan dikirim lewat slot
    `filters` dan cukup berupa <select name="..."> biasa; formnya method GET,
    jadi nilainya otomatis jadi query string yang dibaca TableBuilder.
--}}

<form method="GET" class="flex flex-wrap items-end gap-3">
    <div class="relative grow sm:max-w-xs">
        <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
            <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
            </svg>
        </div>

        <input type="search"
               name="{{ $table?->searchParameter() ?? 'q' }}"
               value="{{ $table?->search() }}"
               placeholder="{{ $placeholder }}"
               class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 ps-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
    </div>

    @isset($filters)
        {{ $filters }}
    @endisset

    <x-ui.button type="submit" variant="secondary" size="sm">Terapkan</x-ui.button>

    @if ($table?->hasActiveFilters())
        <a href="{{ $table->resetUrl() }}" class="text-sm text-gray-500 underline hover:text-gray-700 dark:text-gray-400">
            Reset
        </a>
    @endif
</form>
