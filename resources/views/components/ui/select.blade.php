@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
])

@php
    $errorKey = str_replace(['[', ']'], ['.', ''], $name);
    $invalid = $errors->has($errorKey);
    $id = $attributes->get('id', $name);
    $current = old($errorKey, $selected);
    $multiple = $attributes->has('multiple');
    $currentValues = $multiple ? (array) $current : [$current];
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}"
               class="mb-2 block text-sm font-medium {{ $invalid ? 'text-red-700 dark:text-red-500' : 'text-gray-900 dark:text-white' }}">
            {{ $label }}
            @if ($required)
                <span class="text-red-600" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <select id="{{ $id }}"
            name="{{ $multiple ? $name.'[]' : $name }}"
            @required($required)
            {{ $attributes->class([
                'block w-full rounded-lg border p-2.5 text-sm',
                'border-gray-300 bg-gray-50 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white' => ! $invalid,
                'border-red-500 bg-red-50 text-red-900 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:bg-gray-700 dark:text-red-500' => $invalid,
            ]) }}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected(in_array((string) $optionValue, array_map('strval', $currentValues), true))>
                {{ $optionLabel }}
            </option>
        @endforeach

        {{ $slot }}
    </select>

    @if ($invalid)
        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $errors->first($errorKey) }}</p>
    @elseif ($hint)
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>
