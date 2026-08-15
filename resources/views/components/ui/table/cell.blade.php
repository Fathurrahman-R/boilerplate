@props([
    'header' => false,
    'align' => 'left',
])

@php
    $classes = [
        'px-6 py-4 align-middle',
        'text-right' => $align === 'right',
        'text-center' => $align === 'center',
        'font-medium text-gray-900 whitespace-nowrap dark:text-white' => $header,
    ];
@endphp

@if ($header)
    <th scope="row" {{ $attributes->class($classes) }}>{{ $slot }}</th>
@else
    <td {{ $attributes->class($classes) }}>{{ $slot }}</td>
@endif
