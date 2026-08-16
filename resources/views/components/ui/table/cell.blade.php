@props([
    'header' => false,
    'align' => 'left',
    'numeric' => false,
])

@php
    // Angka selalu mono dan rata kanan supaya digitnya lurus antar-baris.
    $classes = [
        'px-4 py-[11px] align-middle',
        'text-right' => $align === 'right' || $numeric,
        'text-center' => $align === 'center',
        'num text-[12.5px]' => $numeric,
        'font-medium whitespace-nowrap text-ink' => $header,
    ];
@endphp

@if ($header)
    <th scope="row" {{ $attributes->class($classes) }}>{{ $slot }}</th>
@else
    <td {{ $attributes->class($classes) }}>{{ $slot }}</td>
@endif
