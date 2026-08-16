@props([
    'lines' => 3,
    'height' => '12px',
])

{{--
    Penanda muatan untuk konten yang bentuknya sudah diketahui. Lebar tiap
    baris sengaja berbeda-beda supaya terbaca sebagai teks, bukan sebagai balok.
--}}

@php
    $widths = ['38%', '72%', '56%', '64%', '45%', '80%'];
@endphp

<div {{ $attributes->class('flex flex-col gap-2.5') }} role="status" aria-label="Memuat">
    @for ($i = 0; $i < $lines; $i++)
        <div class="skeleton-line rounded-[4px]"
             style="height:{{ $height }};width:{{ $widths[$i % count($widths)] }}"></div>
    @endfor

    <span class="sr-only">Memuat…</span>
</div>
