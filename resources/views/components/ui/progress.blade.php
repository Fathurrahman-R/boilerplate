@props([
    'value' => 0,
    'max' => 100,
    'label' => null,
    'caption' => null,
    // 'accent' atau 'chart-1'..'chart-6'
    'tone' => 'accent',
    'height' => 6,
])

@php
    $percent = $max > 0 ? max(0, min(100, round(($value / $max) * 100, 1))) : 0;

    $fill = $tone === 'accent'
        ? 'bg-accent'
        : 'bg-'.$tone;
@endphp

<div {{ $attributes->class('flex flex-col gap-1.5') }}>
    @if ($label || $caption)
        <div class="flex items-baseline justify-between gap-3 text-[13px]">
            <span class="text-ink">{{ $label }}</span>
            @if ($caption)
                <span class="num text-sm text-ink-muted">{{ $caption }}</span>
            @endif
        </div>
    @endif

    <div class="overflow-hidden rounded-full bg-fill-4"
 style="height: {{ $height }}px"
 role="progressbar" aria-valuenow="{{ $value }}" aria-valuemin="0" aria-valuemax="{{ $max }}">
        <div class="h-full rounded-full transition-[width] duration-[--dur-base] ease-[var(--ease-out-apple)] {{ $fill }}"
 style="width: {{ $percent }}%"></div>
    </div>
</div>
