@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'submit',
    'icon' => null,
    'block' => false,
    'disabled' => false,
])

@php
    $variants = [
        'primary'   => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
        'secondary' => 'text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700',
        'success'   => 'text-white bg-green-700 hover:bg-green-800 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800',
        'danger'    => 'text-white bg-red-700 hover:bg-red-800 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900',
        'warning'   => 'text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-yellow-300 dark:focus:ring-yellow-900',
        'dark'      => 'text-white bg-gray-800 hover:bg-gray-900 focus:ring-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700',
        'ghost'     => 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 focus:ring-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700',
    ];

    $sizes = [
        'xs' => 'px-3 py-2 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-5 py-3 text-base',
    ];

    $classes = implode(' ', [
        'inline-flex items-center justify-center gap-2 rounded-lg font-medium',
        'focus:outline-none focus:ring-4 transition-colors',
        'disabled:pointer-events-none disabled:opacity-50',
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['md'],
        $block ? 'w-full' : '',
    ]);
@endphp

@if ($href && ! $disabled)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        {{ $icon }}
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @disabled($disabled) {{ $attributes->class($classes) }}>
        {{ $icon }}
        {{ $slot }}
    </button>
@endif
