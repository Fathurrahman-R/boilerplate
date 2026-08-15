@props([
    'name',
    'label' => null,
    'checked' => false,
    'value' => '1',
    'hint' => null,
])

@php
    $id = $attributes->get('id', $name);
@endphp

<div>
    <label for="{{ $id }}" class="inline-flex cursor-pointer items-center gap-3">
        {{-- Nilai "0" dikirim lebih dulu supaya field tetap terkirim saat
             togglenya mati; checkbox yang tidak dicentang tidak ikut terkirim. --}}
        <input type="hidden" name="{{ $name }}" value="0">

        <input type="checkbox"
               id="{{ $id }}"
               name="{{ $name }}"
               value="{{ $value }}"
               class="peer sr-only"
               @checked($checked)
               {{ $attributes }}>

        <div class="peer relative h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:ring-4 peer-focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:peer-focus:ring-blue-800 rtl:peer-checked:after:-translate-x-full"></div>

        @if ($label)
            <span class="text-sm font-medium text-gray-900 dark:text-gray-300">{{ $label }}</span>
        @endif
    </label>

    @if ($hint)
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>
