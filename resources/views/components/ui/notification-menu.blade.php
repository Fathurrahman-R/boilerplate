@props([
    // Tiap item: ['icon' => 'receipt', 'tone' => 'accent|success|warning|danger',
    //             'text' => '…', 'time' => '2 jam lalu', 'url' => null]
    'items' => [],
    'url' => null,
])

@php
    $tones = [
        'accent' => 'text-accent',
        'success' => 'text-success',
        'warning' => 'text-warning',
        'danger' => 'text-danger',
        'muted' => 'text-ink-muted',
    ];
@endphp

<div x-data="{ open: false }"
     x-on:keydown.escape.window="if (open) { open = false; $refs.trigger?.focus() }"
     x-on:click.outside="open = false"
     class="relative inline-flex">

    <button type="button" x-ref="trigger" x-on:click="open = !open"
            :aria-expanded="open" aria-haspopup="menu"
            x-data="pressable()" x-bind="pressBind"
            class="relative inline-flex size-9 items-center justify-center rounded-md bg-fill-3 text-ink-secondary outline-none focus-visible:shadow-[var(--focus-ring)]">
        <span class="sr-only">Notifikasi</span>
        <x-ui.icon name="bell" class="size-[17px]" />

        @if ($items !== [])
            {{-- Titik, bukan angka: jumlah pastinya ada di dalam menu. --}}
            <span class="absolute end-[7px] top-[6px] size-[7px] rounded-full bg-danger ring-[1.5px] ring-surface-raised"></span>
        @endif
    </button>

    <div role="menu" x-show="open" x-cloak
         x-spring="{ from: { opacity: 0, scale: 0.94, y: -4, filter: 'blur(8px)' },
                     to:   { opacity: 1, scale: 1,    y: 0,  filter: 'blur(0px)' },
                     token: 'sheet', exitToken: 'overlay', origin: 'bottom-end' }"
         data-mat="thin"
         class="material absolute end-0 top-full z-50 mt-1.5 w-[320px] overflow-hidden rounded-lg">

        <div class="vibrant px-3.5 py-3 text-base font-semibold">Notifikasi</div>

        @forelse ($items as $item)
            <a @if ($item['url'] ?? null) href="{{ $item['url'] }}" @endif
               class="relative flex gap-2.5 px-3.5 py-3 transition-colors duration-[--dur-fast] hover:bg-fill-3
                      not-first:before:absolute not-first:before:inset-x-3.5 not-first:before:top-0
                      not-first:before:h-px not-first:before:bg-line not-first:before:content-['']">
                <x-ui.icon :name="$item['icon'] ?? 'info'"
                           class="mt-px size-4 shrink-0 {{ $tones[$item['tone'] ?? 'muted'] ?? $tones['muted'] }}" />
                <div class="min-w-0 flex-1">
                    <div class="text-base text-ink">{{ $item['text'] }}</div>
                    @if ($item['time'] ?? null)
                        <div class="mt-0.5 text-xs text-ink-muted">{{ $item['time'] }}</div>
                    @endif
                </div>
            </a>
        @empty
            <p class="px-3.5 py-6 text-center text-base text-ink-muted">Belum ada notifikasi.</p>
        @endforelse

        @if ($url)
            <a href="{{ $url }}" class="block px-3.5 py-2.5 text-center text-base font-medium text-accent transition hover:bg-fill-3">
                Lihat semua
            </a>
        @endif
    </div>
</div>
