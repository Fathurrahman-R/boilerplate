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

{{-- Jeda sebelum muncul, tanpa jeda saat pergi: tooltip yang langsung
     menyembul mengganggu kursor yang cuma lewat, sedangkan yang lambat
     menghilang menutupi apa yang mau diklik berikutnya. --}}
<span x-data="{ show: false, timer: null,
        enter() { clearTimeout(this.timer); this.timer = setTimeout(() => this.show = true, 450) },
        leave() { clearTimeout(this.timer); this.show = false } }"
      x-on:mouseenter="enter()"
      x-on:mouseleave="leave()"
      x-on:focusin="show = true"
      x-on:focusout="leave()"
      {{ $attributes->class('relative inline-flex') }}>
    {{ $slot }}

    {{-- Chip tinta padat, bukan material: lapisan translusen di atas lapisan
         translusen membuat teksnya hilang, dan tooltip harus selalu terbaca. --}}
    <span role="tooltip" x-show="show" x-cloak
          x-spring="{ from: { opacity: 0, scale: 0.94 }, to: { opacity: 1, scale: 1 }, token: 'overlay' }"
          class="pointer-events-none absolute z-[60] {{ $anchors[$placement] ?? $anchors['top'] }} rounded-md bg-ink px-2.5 py-1.5 text-sm whitespace-nowrap text-surface-raised shadow-md">
        {{ $text }}
    </span>
</span>
