@props([
    'variant' => 'neutral',
    'size' => 'sm',
    'pill' => false,
    'dot' => false,
])

@php
    $variants = [
        'neutral' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        'primary' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'success' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'danger'  => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'info'    => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
        'purple'  => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
    ];

    $dots = [
        'neutral' => 'bg-gray-500',
        'primary' => 'bg-blue-500',
        'success' => 'bg-green-500',
        'danger'  => 'bg-red-500',
        'warning' => 'bg-yellow-500',
        'info'    => 'bg-indigo-500',
        'purple'  => 'bg-purple-500',
    ];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center gap-1.5 font-medium',
    'text-xs px-2 py-0.5' => $size === 'sm',
    'text-sm px-2.5 py-1' => $size === 'md',
    'rounded-full' => $pill,
    'rounded' => ! $pill,
    $variants[$variant] ?? $variants['neutral'],
]) }}>
    @if ($dot)
        <span class="h-2 w-2 rounded-full {{ $dots[$variant] ?? $dots['neutral'] }}"></span>
    @endif

    {{ $slot }}
</span>
