@props([
    // 'raised' — tuts yang berdiri sendiri di dalam teks atau menu.
    // 'well'   — tuts di dalam kolom yang sudah cekung; kalau ikut menonjol,
    //            dua arah cahaya bertabrakan di satu kontrol.
    'variant' => 'raised',
])

@php
    $variants = [
        'raised' => 'border border-line bg-[image:var(--mat-raised)] shadow-lift text-ink-secondary',
        'well' => 'bg-surface-sunken shadow-well text-ink-muted',
    ];
@endphp

<kbd {{ $attributes->class([
    'inline-flex items-center rounded-[5px] px-1.5 py-px font-mono text-[11px] leading-[1.6] whitespace-nowrap',
    $variants[$variant] ?? $variants['raised'],
]) }}>{{ $slot }}</kbd>
