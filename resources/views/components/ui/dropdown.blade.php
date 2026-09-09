@props([
    'id' => null,
    'label' => null,
    'placement' => 'bottom',
    'width' => 'w-56',
])

{{--
    Menu bertingkat lebih dari satu tidak dipakai. Kalau butuh submenu, yang
    dibutuhkan sebenarnya halaman tersendiri.

    Menunya tumbuh dari tombol yang membukanya — titik asal transform-nya
    diturunkan dari `placement`. Kalau sesuatu muncul dari satu tempat, orang
    menunggunya kembali ke tempat yang sama, dan hubungan antara tombol dan
    isinya jadi jelas tanpa perlu dijelaskan.

    Panah atas-bawah menjelajah isinya. Sebelumnya menu ini memasang
    role="menu" tapi papan ketik tidak melakukan apa-apa di dalamnya — janji
    yang tidak ditepati.
--}}

@php
    $id ??= 'dropdown-'.Str::random(8);

    $anchors = [
        'bottom' => 'top-full mt-1.5 start-0',
        'bottom-start' => 'top-full mt-1.5 start-0',
        'bottom-end' => 'top-full mt-1.5 end-0',
        'top' => 'bottom-full mb-1.5 start-0',
        'top-end' => 'bottom-full mb-1.5 end-0',
    ];
@endphp

<div x-data="anchoredMenu()"
     x-on:keydown.escape.window="close()"
     x-on:click.outside="close({ restoreFocus: false })"
     class="relative inline-block">

    @isset($trigger)
        {{-- Trigger kustom: pemanggil yang menentukan tampilannya. --}}
        <button type="button" x-ref="trigger" x-on:click="toggle()"
                x-on:keydown.arrow-down.prevent="show(); $nextTick(() => first())"
                :aria-expanded="open" aria-haspopup="menu" aria-controls="{{ $id }}"
                class="flex cursor-pointer items-center rounded-full outline-none focus-visible:shadow-[var(--focus-ring)]">
            {{ $trigger }}
        </button>
    @else
        <button type="button" x-ref="trigger" x-on:click="toggle()"
                x-on:keydown.arrow-down.prevent="show(); $nextTick(() => first())"
                x-data="pressable()" x-bind="pressBind"
                :aria-expanded="open" aria-haspopup="menu" aria-controls="{{ $id }}"
                {{ $attributes->class('inline-flex h-control items-center gap-2 rounded-md bg-fill-3 px-4 text-base font-medium text-ink outline-none transition-colors duration-[--dur-fast] hover:bg-fill-2 focus-visible:shadow-[var(--focus-ring)]') }}>
            {{ $label }}
            <x-ui.icon name="chevron-down" class="size-4" />
        </button>
    @endisset

    <div id="{{ $id }}" x-ref="menu" role="menu" x-show="open" x-cloak
         x-spring="{ from: { opacity: 0, scale: 0.94, y: -4, filter: 'blur(8px)' },
                     to:   { opacity: 1, scale: 1,    y: 0,  filter: 'blur(0px)' },
                     token: 'sheet', exitToken: 'overlay', origin: @js($placement) }"
         x-on:keydown.arrow-down.prevent="move(1)"
         x-on:keydown.arrow-up.prevent="move(-1)"
         x-on:keydown.home.prevent="first()"
         x-on:keydown.end.prevent="last()"
         x-on:keydown.tab="close({ restoreFocus: false })"
         data-mat="thin"
         class="material absolute z-50 {{ $anchors[$placement] ?? $anchors['bottom'] }} {{ $width }} rounded-lg p-1.5">
        <ul class="text-base text-ink">
            {{ $slot }}
        </ul>
    </div>
</div>
