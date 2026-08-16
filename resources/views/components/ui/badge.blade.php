@props([
    'variant' => 'neutral',
    'size' => 'sm',
    'pill' => false,
    'dot' => false,
])

@php
    // Warna status hanya dipakai untuk menyampaikan arti. Badge netral adalah
    // bawaannya justru supaya warna tidak kehilangan daya bicaranya.
    $variants = [
        'neutral' => 'bg-surface-inset text-ink-secondary',
        'primary' => 'bg-accent-soft text-accent',
        'success' => 'bg-success-soft text-success',
        'danger' => 'bg-danger-soft text-danger',
        'warning' => 'bg-warning-soft text-warning',
        'info' => 'bg-info-soft text-info',
        'purple' => 'bg-[color-mix(in_oklab,var(--c4)_14%,transparent)] text-chart-4',
    ];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center gap-1.5 font-semibold whitespace-nowrap',
    'px-2.5 py-0.5 text-xs' => $size === 'sm',
    'px-3 py-1 text-[13px]' => $size === 'md',
    'rounded-full' => $pill,
    'rounded-sm' => ! $pill,
    $variants[$variant] ?? $variants['neutral'],
]) }}>
    @if ($dot)
        <span class="size-[5px] shrink-0 rounded-full bg-current"></span>
    @endif

    {{ $slot }}
</span>
