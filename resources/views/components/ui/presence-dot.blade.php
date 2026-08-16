@props([
    // 'online' | 'away' | 'offline'
    'status' => 'online',
])

@php
    $tones = [
        'online' => 'bg-success',
        'away' => 'bg-warning',
        'offline' => 'bg-line-strong',
    ];

    $labels = [
        'online' => 'Sedang aktif',
        'away' => 'Sedang pergi',
        'offline' => 'Tidak aktif',
    ];
@endphp

<span {{ $attributes->class([
    'absolute end-[-1px] bottom-[-1px] size-[11px] rounded-full ring-2 ring-surface-raised',
    $tones[$status] ?? $tones['offline'],
]) }} title="{{ $labels[$status] ?? '' }}">
    <span class="sr-only">{{ $labels[$status] ?? '' }}</span>
</span>
