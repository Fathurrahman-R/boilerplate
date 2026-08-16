{{--
    Menampilkan pesan flash dari session sebagai toast melayang.

        return redirect()->route('admin.users.index')->with('success', 'User disimpan.');

    Kunci yang dikenali: success, error, warning, info.

    Toast dipakai untuk konfirmasi yang lewat begitu saja. Kalau pesannya butuh
    tindakan, tempatnya di alert atau modal — bukan di sini.
--}}

@php
    $messages = collect(['success', 'error', 'warning', 'info'])
        ->filter(fn (string $key): bool => session()->has($key))
        ->map(fn (string $key): array => ['type' => $key, 'message' => session($key)])
        ->values();

    $styles = [
        'success' => ['icon' => 'circle-check', 'fg' => 'text-success'],
        'error' => ['icon' => 'circle-x', 'fg' => 'text-danger'],
        'warning' => ['icon' => 'triangle-alert', 'fg' => 'text-warning'],
        'info' => ['icon' => 'info', 'fg' => 'text-info'],
    ];
@endphp

@if ($messages->isNotEmpty())
    <div class="fixed end-6 bottom-6 z-[90] flex flex-col gap-3">
        @foreach ($messages as $item)
            @php($style = $styles[$item['type']] ?? $styles['info'])

            <div role="status"
                 x-data="{ show: true }"
                 x-init="setTimeout(() => show = false, 6000)"
                 x-show="show" x-cloak
                 x-transition:enter="transition duration-260 ease-rizz"
                 x-transition:enter-start="translate-y-3 scale-[0.97] opacity-0"
                 class="flex w-full max-w-[340px] items-center gap-3 rounded-lg border border-line bg-surface-raised p-3.5 shadow-lg">
                <x-ui.icon :name="$style['icon']" class="size-[18px] shrink-0 {{ $style['fg'] }}" />

                <div class="flex-1 text-sm text-ink">{{ $item['message'] }}</div>

                <button type="button" x-on:click="show = false"
                        class="shrink-0 text-ink-muted transition hover:text-ink focus-visible:outline-none focus-visible:ring-3 focus-visible:ring-accent-soft">
                    <span class="sr-only">Tutup</span>
                    <x-ui.icon name="x" class="size-4" />
                </button>
            </div>
        @endforeach
    </div>
@endif
