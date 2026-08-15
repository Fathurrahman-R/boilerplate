@props([
    'name',
    'label' => null,
    'value' => null,
    'checked' => false,
    'hint' => null,
])

@php
    $id = $attributes->get('id', $name.'_'.$value);
@endphp

<div class="flex items-start gap-2">
    <div class="flex h-5 items-center">
        <input type="radio"
               id="{{ $id }}"
               name="{{ $name }}"
               value="{{ $value }}"
               @checked($checked)
               {{ $attributes->class('h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-blue-600') }}>
    </div>

    @if ($label || $hint)
        <div class="text-sm">
            <label for="{{ $id }}" class="font-medium text-gray-900 dark:text-gray-300">{{ $label }}</label>

            @if ($hint)
                <p class="text-xs font-normal text-gray-500 dark:text-gray-400">{{ $hint }}</p>
            @endif
        </div>
    @endif
</div>
