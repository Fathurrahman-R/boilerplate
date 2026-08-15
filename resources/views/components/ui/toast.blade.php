{{--
    Menampilkan pesan flash dari session sebagai toast melayang.

        return redirect()->route('admin.users.index')->with('success', 'User disimpan.');

    Kunci yang dikenali: success, error, warning, info.
--}}

@php
    $messages = collect(['success', 'error', 'warning', 'info'])
        ->filter(fn (string $key): bool => session()->has($key))
        ->map(fn (string $key): array => ['type' => $key, 'message' => session($key)]);

    $styles = [
        'success' => ['bg-green-100 text-green-500 dark:bg-green-800 dark:text-green-200', 'M10 .5a9.5 9.5 0 1 0 0 19 9.5 9.5 0 0 0 0-19Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z'],
        'error'   => ['bg-red-100 text-red-500 dark:bg-red-800 dark:text-red-200', 'M10 .5a9.5 9.5 0 1 0 0 19 9.5 9.5 0 0 0 0-19ZM6.293 6.293a1 1 0 0 1 1.414 0L10 8.586l2.293-2.293a1 1 0 1 1 1.414 1.414L11.414 10l2.293 2.293a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 0-1.414Z'],
        'warning' => ['bg-yellow-100 text-yellow-500 dark:bg-yellow-800 dark:text-yellow-200', 'M10 .5a9.5 9.5 0 1 0 0 19 9.5 9.5 0 0 0 0-19ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z'],
        'info'    => ['bg-blue-100 text-blue-500 dark:bg-blue-800 dark:text-blue-200', 'M10 .5a9.5 9.5 0 1 0 0 19 9.5 9.5 0 0 0 0-19ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z'],
    ];
@endphp

@if ($messages->isNotEmpty())
    <div class="fixed bottom-5 end-5 z-50 flex flex-col gap-3">
        @foreach ($messages as $index => $item)
            @php($style = $styles[$item['type']] ?? $styles['info'])

            <div id="toast-{{ $index }}" role="alert"
                 class="flex w-full max-w-xs items-center rounded-lg bg-white p-4 text-gray-500 shadow-sm dark:bg-gray-800 dark:text-gray-400">
                <div class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $style[0] }}">
                    <svg class="h-4 w-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path d="{{ $style[1] }}"/>
                    </svg>
                </div>

                <div class="ms-3 text-sm font-normal">{{ $item['message'] }}</div>

                <button type="button" data-dismiss-target="#toast-{{ $index }}" aria-label="Tutup"
                        class="-mx-1.5 -my-1.5 ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:bg-gray-800 dark:text-gray-500 dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg class="h-3 w-3" aria-hidden="true" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
        @endforeach
    </div>
@endif
