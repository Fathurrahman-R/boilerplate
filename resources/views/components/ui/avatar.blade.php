@props([
    'user' => null,
    'src' => null,
    'alt' => null,
    'size' => 'md',
    // 'round' untuk orang, 'square' untuk badan usaha atau entitas lain —
    // bentuknya yang membedakan, bukan isinya.
    'shape' => 'round',
])

@php
    $sizes = [
        'xs' => 'size-6',
        'xs2' => 'size-7',
        'sm' => 'size-8',
        'md' => 'size-10',
        'lg' => 'size-16',
        'xl' => 'size-24',
    ];

    $src ??= $user?->avatarUrl();
    $alt ??= $user?->name ?? 'Avatar';
@endphp

<img src="{{ $src }}" alt="{{ $alt }}"
     {{ $attributes->class([
         'shrink-0 object-cover ring-1 ring-line',
         'rounded-full' => $shape !== 'square',
         'rounded-md' => $shape === 'square',
         $sizes[$size] ?? $sizes['md'],
     ]) }}>
