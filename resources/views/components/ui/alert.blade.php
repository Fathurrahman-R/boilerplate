@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => false,
])

@php
    $variants = [
        'info' => ['bg' => 'bg-info-soft', 'fg' => 'text-info', 'icon' => 'info'],
        'success' => ['bg' => 'bg-success-soft', 'fg' => 'text-success', 'icon' => 'circle-check'],
        'warning' => ['bg' => 'bg-warning-soft', 'fg' => 'text-warning', 'icon' => 'triangle-alert'],
        'danger' => ['bg' => 'bg-danger-soft', 'fg' => 'text-danger', 'icon' => 'circle-x'],
        'neutral' => ['bg' => 'bg-surface-inset', 'fg' => 'text-ink-secondary', 'icon' => 'info'],
    ];

    $style = $variants[$variant] ?? $variants['info'];
@endphp

{{-- Sela orang hanya kalau perlu keputusan. Alert menjelaskan keadaan; kalau
     butuh jawaban, yang dipakai modal. --}}

<div role="alert"
     @if ($dismissible) x-data="{ show: true }" x-show="show" x-cloak @endif
     {{ $attributes->class(['flex items-start gap-3 rounded-md border border-line px-4 py-3.5 text-sm text-ink', $style['bg']]) }}>
    <x-ui.icon :name="$style['icon']" class="mt-0.5 size-[18px] shrink-0 {{ $style['fg'] }}" />

    <div class="flex-1">
        @if ($title)
            <div class="font-semibold">{{ $title }}</div>
            <div class="mt-0.5 text-[13.5px] text-ink-secondary">{{ $slot }}</div>
        @else
            {{ $slot }}
        @endif
    </div>

    @if ($dismissible)
        <button type="button"
                x-on:click="show = false"
                class="-my-1 -me-1 inline-flex size-7 shrink-0 items-center justify-center rounded-sm text-ink-muted transition hover:bg-black/5 hover:text-ink focus-visible:outline-none focus-visible:ring-3 focus-visible:ring-accent-soft">
            <span class="sr-only">Tutup</span>
            <x-ui.icon name="x" class="size-4" />
        </button>
    @endif
</div>
