@props([
    // [['label' => 'Lunas', 'value' => 62, 'tone' => 'chart-1'], …]
    'segments' => [],
    'height' => 11,
])

@php
    $total = array_sum(array_column($segments, 'value')) ?: 1;
@endphp

{{-- Satu batang untuk komposisi. Celah 2px antar segmen menggantikan garis
     pembatas: warnanya sudah berbeda, garis hanya menambah dengung. --}}

<div {{ $attributes->class('flex gap-[2px] overflow-hidden rounded-full') }} style="height: {{ $height }}px">
    @foreach ($segments as $segment)
        <div class="bg-{{ $segment['tone'] ?? 'chart-1' }}"
             style="width: {{ round(($segment['value'] / $total) * 100, 2) }}%"
             title="{{ $segment['label'] ?? '' }}"></div>
    @endforeach
</div>
