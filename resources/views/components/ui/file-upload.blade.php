@props([
    'name',
    'label' => null,
    'hint' => null,
    'accept' => null,
])

@php
    $errorKey = str_replace(['[', ']'], ['.', ''], $name);
    $invalid = $errors->has($errorKey);
    $id = $attributes->get('id', $name);
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</label>
    @endif

    <input type="file"
           id="{{ $id }}"
           name="{{ $name }}"
           @if ($accept) accept="{{ $accept }}" @endif
           {{ $attributes->class([
               'block w-full cursor-pointer rounded-lg border text-sm',
               'file:me-3 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-gray-700 dark:file:bg-gray-600 dark:file:text-gray-200',
               'border-gray-300 bg-gray-50 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400' => ! $invalid,
               'border-red-500 bg-red-50 text-red-900 dark:border-red-500 dark:bg-gray-700' => $invalid,
           ]) }}>

    @if ($invalid)
        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $errors->first($errorKey) }}</p>
    @elseif ($hint)
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>
