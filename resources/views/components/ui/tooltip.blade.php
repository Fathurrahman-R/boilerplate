@props([
    'text',
    'placement' => 'top',
])

{{--
    Tooltip menjelaskan, bukan mengulang label. Pemicunya dibungkus di slot:

        <x-ui.tooltip text="Menghapus permanen">
            <x-ui.button variant="ghost"><x-ui.icon name="trash-2" /></x-ui.button>
        </x-ui.tooltip>

    Muncul saat kursor melintas maupun saat elemen di dalamnya mendapat fokus
    keyboard, jadi isinya tidak hilang untuk yang tidak memakai tetikus.
--}}

@php
    $anchors = [
        'top' => 'bottom-full mb-2 left-1/2 -translate-x-1/2',
        'bottom' => 'top-full mt-2 left-1/2 -translate-x-1/2',
        'start' => 'end-full me-2 top-1/2 -translate-y-1/2',
        'end' => 'start-full ms-2 top-1/2 -translate-y-1/2',
    ];
@endphp

<span x-data="{ show: false }"
      x-on:mouseenter="show = true"
      x-on:mouseleave="show = false"
      x-on:focusin="show = true"
      x-on:focusout="show = false"
      {{ $attributes->class('relative inline-flex') }}>
    {{ $slot }}

    <span role="tooltip" x-show="show" x-cloak
          x-transition:enter="transition duration-140 ease-out"
          x-transition:enter-start="opacity-0"
          class="pointer-events-none absolute z-[60] {{ $anchors[$placement] ?? $anchors['top'] }} rounded-sm bg-ink px-2.5 py-1.5 text-[12.5px] whitespace-nowrap text-surface-raised shadow-md">
        {{ $text }}
    </span>
</span>
