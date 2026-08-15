@props([
    'id',
    'title' => null,
    'size' => 'md',
])

{{--
    Modal Flowbite. Buka dari mana pun dengan atribut data:

        <x-ui.button data-modal-target="hapus-user" data-modal-toggle="hapus-user">Hapus</x-ui.button>

    Isi slot `footer` untuk tombol aksi.
--}}

@php
    $sizes = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
    ];
@endphp

<div id="{{ $id }}" tabindex="-1" aria-hidden="true"
     class="fixed inset-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden p-4 md:inset-0">
    <div class="relative max-h-full w-full {{ $sizes[$size] ?? $sizes['md'] }}">
        <div class="relative rounded-lg bg-white shadow-sm dark:bg-gray-700">
            <div class="flex items-center justify-between rounded-t border-b border-gray-200 p-4 dark:border-gray-600 md:p-5">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>

                <button type="button"
                        class="ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="{{ $id }}">
                    <svg class="h-3 w-3" aria-hidden="true" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Tutup</span>
                </button>
            </div>

            <div class="space-y-4 p-4 text-sm text-gray-600 dark:text-gray-300 md:p-5">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="flex items-center justify-end gap-2 rounded-b border-t border-gray-200 p-4 dark:border-gray-600 md:p-5">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
