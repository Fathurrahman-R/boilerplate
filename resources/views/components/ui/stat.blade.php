@props([
    'label',
    'value',
    'delta' => null,
    'trend' => null,
    'icon' => null,
    'glass' => true,
])

{{--
    Kartu metrik: label mono huruf besar, angka besar dengan digit tabular,
 lalu perubahannya. Versi kaca hanya masuk akal di atas latar bertekstur —
 setel :glass="false" kalau ditaruh di dalam card biasa.

    $trend: 'up' | 'down' | 'flat'. Warna hanya dipakai kalau arahnya memang
 punya arti; 'flat' sengaja abu-abu.
--}}

@php
    $trends = [
        'up' => ['icon' => 'trending-up', 'fg' => 'text-success'],
        'down' => ['icon' => 'trending-down', 'fg' => 'text-danger'],
        'flat' => ['icon' => 'minus', 'fg' => 'text-ink-muted'],
    ];

    $style = $trends[$trend] ?? null;
@endphp

<div @if ($glass) data-mat="thin" @endif
     {{ $attributes->class([
         'rounded-xl p-4',
         'material' => $glass,
         'border-[0.5px] border-line bg-surface-raised shadow-sm' => ! $glass,
     ]) }}>
    <div class="flex items-start justify-between gap-3">
        <span class="eyebrow">{{ $label }}</span>

        @if ($icon)
            <x-ui.icon :name="$icon" class="size-4 text-ink-muted" />
        @endif
    </div>

    <div class="num mt-1.5 text-3xl font-semibold text-ink">{{ $value }}</div>

    @if ($delta)
        <div class="mt-1 flex items-center gap-1.5 text-sm {{ $style['fg'] ?? 'text-ink-muted' }}">
            @if ($style)
                <x-ui.icon :name="$style['icon']" class="size-3.5" />
            @endif
            {{ $delta }}
        </div>
    @endif
</div>
