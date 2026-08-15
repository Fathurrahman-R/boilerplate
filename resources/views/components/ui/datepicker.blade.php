@props([
    'name',
    'label' => null,
    'value' => null,
    'hint' => null,
    'format' => 'dd/mm/yyyy',
])

{{--
    Datepicker Flowbite. Atribut datepicker* dibaca oleh initFlowbite(), jadi
    komponen ini juga hidup di markup yang baru dimasukkan ke DOM asal event
    `content:updated` dipancarkan.
--}}

@php
    $errorKey = str_replace(['[', ']'], ['.', ''], $name);
    $invalid = $errors->has($errorKey);
    $id = $attributes->get('id', $name);
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</label>
    @endif

    <div class="relative">
        <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
            <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Z"/>
            </svg>
        </div>

        <input type="text"
               id="{{ $id }}"
               name="{{ $name }}"
               value="{{ old($errorKey, $value) }}"
               datepicker
               datepicker-autohide
               datepicker-format="{{ $format }}"
               datepicker-buttons
               datepicker-autoselect-today="false"
               placeholder="{{ $format }}"
               {{ $attributes->class([
                   'block w-full rounded-lg border p-2.5 ps-10 text-sm',
                   'border-gray-300 bg-gray-50 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white' => ! $invalid,
                   'border-red-500 bg-red-50 text-red-900 dark:border-red-500 dark:bg-gray-700' => $invalid,
               ]) }}>
    </div>

    @if ($invalid)
        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $errors->first($errorKey) }}</p>
    @elseif ($hint)
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>
