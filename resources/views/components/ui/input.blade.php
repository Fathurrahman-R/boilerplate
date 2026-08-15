@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'hint' => null,
    'required' => false,
    'prefix' => null,
])

@php
    // Nama field boleh berbentuk array (mis. "items[0][qty]"); $errors memakai
    // notasi titik, jadi diterjemahkan lebih dulu.
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

    <div class="relative">
        @if ($prefix)
            <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3 text-gray-500 dark:text-gray-400">
                {{ $prefix }}
            </div>
        @endif

        <input type="{{ $type }}"
               id="{{ $id }}"
               name="{{ $name }}"
               value="{{ old($errorKey, $value) }}"
               @required($required)
               @if ($invalid) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
               {{ $attributes->class([
                   'block w-full rounded-lg border p-2.5 text-sm',
                   'ps-10' => (bool) $prefix,
                   'border-gray-300 bg-gray-50 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500' => ! $invalid,
                   'border-red-500 bg-red-50 text-red-900 placeholder-red-700 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:bg-gray-700 dark:text-red-500 dark:placeholder-red-500' => $invalid,
               ]) }}>
    </div>

    @if ($invalid)
        <p id="{{ $id }}-error" class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $errors->first($errorKey) }}</p>
    @elseif ($hint)
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>
