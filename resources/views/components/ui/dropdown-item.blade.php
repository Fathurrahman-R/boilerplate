@props([
    'href' => null,
    'danger' => false,
])

@php
    $classes = implode(' ', [
        'flex w-full items-center gap-2 px-4 py-2 text-left',
        $danger
            ? 'text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-gray-600'
            : 'hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white',
    ]);
@endphp

<li>
    @if ($href)
        <a href="{{ $href }}" {{ $attributes->class($classes) }}>{{ $slot }}</a>
    @else
        <button type="button" {{ $attributes->class($classes) }}>{{ $slot }}</button>
    @endif
</li>
