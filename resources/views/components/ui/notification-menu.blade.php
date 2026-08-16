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
            class="relative inline-flex size-[34px] items-center justify-center rounded-md border border-line-strong bg-[image:var(--mat-raised)] text-ink-secondary shadow-[var(--bevel),var(--lift)] transition hover:brightness-95 active:translate-y-px active:shadow-press focus-visible:ring-3 focus-visible:ring-accent-soft focus-visible:outline-none">
        <span class="sr-only">Notifikasi</span>
        <x-ui.icon name="bell" class="size-[17px]" />

        @if ($items !== [])
            {{-- Titik, bukan angka: jumlah pastinya ada di dalam menu. --}}
            <span class="absolute end-[7px] top-[6px] size-[7px] rounded-full bg-danger ring-[1.5px] ring-surface-raised"></span>
        @endif
    </button>

    <div role="menu" x-show="open" x-cloak
         x-transition:enter="transition duration-160 ease-out"
         x-transition:enter-start="translate-y-2 opacity-0"
         class="absolute end-0 top-full z-50 mt-1.5 w-[320px] overflow-hidden rounded-lg border border-line bg-surface-raised shadow-lg">

        <div class="border-b border-line px-3.5 py-3 text-base2 font-semibold text-ink">Notifikasi</div>

        @forelse ($items as $item)
            <a @if ($item['url'] ?? null) href="{{ $item['url'] }}" @endif
               class="flex gap-2.5 border-b border-line px-3.5 py-3 transition hover:bg-surface-inset">
                <x-ui.icon :name="$item['icon'] ?? 'info'"
                           class="mt-px size-4 shrink-0 {{ $tones[$item['tone'] ?? 'muted'] ?? $tones['muted'] }}" />
                <div class="min-w-0 flex-1">
                    <div class="text-base2 text-ink">{{ $item['text'] }}</div>
                    @if ($item['time'] ?? null)
                        <div class="mt-0.5 text-xs2 text-ink-muted">{{ $item['time'] }}</div>
                    @endif
                </div>
            </a>
        @empty
            <p class="px-3.5 py-6 text-center text-base2 text-ink-muted">Belum ada notifikasi.</p>
        @endforelse

        @if ($url)
            <a href="{{ $url }}" class="block px-3.5 py-2.5 text-center text-base2 font-medium text-accent transition hover:bg-surface-inset">
                Lihat semua
            </a>
        @endif
    </div>
</div>
