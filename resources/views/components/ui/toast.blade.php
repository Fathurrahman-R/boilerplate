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
    <div class="fixed end-4 z-[90] flex flex-col gap-2.5"
         style="bottom: calc(1rem + var(--safe-b))">
        @foreach ($messages as $item)
            @php($style = $styles[$item['type']] ?? $styles['info'])

            {{-- Bisa disapu ke samping untuk dibuang, dan keluar lewat sumbu
                 yang sama dengan arah sapuannya. Timernya berhenti selama
                 jari menempel atau kursor melintas — pesan yang sedang dibaca
                 tidak boleh kabur di tengah kalimat. --}}
            <div role="{{ $item['type'] === 'error' ? 'alert' : 'status' }}"
                 aria-live="{{ $item['type'] === 'error' ? 'assertive' : 'polite' }}"
                 x-data="swipeDismiss()" x-init="swipeInit()"
                 x-on:pointerenter="pauseTimer()" x-on:pointerleave="startTimer()"
                 x-show="show" x-cloak
                 x-spring="{ from: { opacity: 0, y: 16, scale: 0.96, filter: 'blur(8px)' },
                             to:   { opacity: 1, y: 0,  scale: 1,    filter: 'blur(0px)' },
                             token: 'sheet' }"
                 data-mat="regular"
                 class="material flex w-full max-w-[340px] cursor-grab touch-pan-y items-center gap-3 rounded-2xl p-3.5 active:cursor-grabbing">
                <x-ui.icon :name="$style['icon']" class="size-[18px] shrink-0 {{ $style['fg'] }}" />

                <div class="vibrant flex-1 text-base">{{ $item['message'] }}</div>

                <button type="button" x-on:click="dismiss()" x-on:pointerdown.stop
                        class="shrink-0 text-ink-muted transition-colors duration-[--dur-fast] hover:text-ink focus-visible:outline-none">
                    <span class="sr-only">Tutup</span>
                    <x-ui.icon name="x" class="size-4" />
                </button>
            </div>
        @endforeach
    </div>
@endif
