@props([
    'user' => null,
    'src' => null,
    'alt' => null,
    'size' => 'md',
])

@php
    $sizes = [
        'xs' => 'h-6 w-6',
        'sm' => 'h-8 w-8',
        'md' => 'h-10 w-10',
        'lg' => 'h-16 w-16',
        'xl' => 'h-24 w-24',
    ];

    $src ??= $user?->avatarUrl();
    $alt ??= $user?->name ?? 'Avatar';
@endphp

<img src="{{ $src }}" alt="{{ $alt }}"
     {{ $attributes->class(['rounded-full object-cover ring-1 ring-gray-200 dark:ring-gray-700', $sizes[$size] ?? $sizes['md']]) }}>
