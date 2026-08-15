@props([
    'name',
    'label' => null,
    'value' => null,
    'hint' => null,
    'rows' => 4,
    'required' => false,
])

@php
    $errorKey = str_replace(['[', ']'], ['.', ''], $name);
    $invalid = $errors->has($errorKey);
    $id = $attributes->get('id', $name);
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

    <textarea id="{{ $id }}"
              name="{{ $name }}"
              rows="{{ $rows }}"
              @required($required)
              {{ $attributes->class([
                  'block w-full rounded-lg border p-2.5 text-sm',
                  'border-gray-300 bg-gray-50 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400' => ! $invalid,
                  'border-red-500 bg-red-50 text-red-900 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:bg-gray-700 dark:text-red-500' => $invalid,
              ]) }}>{{ old($errorKey, $value) }}</textarea>

    @if ($invalid)
        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $errors->first($errorKey) }}</p>
    @elseif ($hint)
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>
