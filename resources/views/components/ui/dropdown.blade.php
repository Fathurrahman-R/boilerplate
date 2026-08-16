@props([
    'id' => null,
    'label' => null,
    'placement' => 'bottom',
    'width' => 'w-56',
])

{{--
    Menu bertingkat lebih dari satu tidak dipakai. Kalau butuh submenu, yang
    dibutuhkan sebenarnya halaman tersendiri.
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

<div x-data="{ open: false }"
     x-on:keydown.escape.window="if (open) { open = false; $refs.trigger?.focus() }"
     x-on:click.outside="open = false"
     class="relative inline-block">

    @isset($trigger)
        {{-- Trigger kustom: pemanggil yang menentukan tampilannya. --}}
        <button type="button" x-ref="trigger" x-on:click="open = !open"
                :aria-expanded="open" aria-haspopup="menu" aria-controls="{{ $id }}"
                class="flex cursor-pointer items-center rounded-full outline-none focus-visible:ring-3 focus-visible:ring-accent-soft">
            {{ $trigger }}
        </button>
    @else
        <button type="button" x-ref="trigger" x-on:click="open = !open"
                :aria-expanded="open" aria-haspopup="menu" aria-controls="{{ $id }}"
                {{ $attributes->class('inline-flex h-control items-center gap-2 rounded-md border border-line-strong bg-[image:var(--mat-raised)] px-4 text-sm font-medium text-ink shadow-[var(--bevel),var(--lift)] outline-none transition active:shadow-press active:translate-y-px focus-visible:ring-3 focus-visible:ring-accent-soft') }}>
            {{ $label }}
            <x-ui.icon name="chevron-down" class="size-4" />
        </button>
    @endisset

    <div id="{{ $id }}" role="menu" x-show="open" x-cloak
         x-transition:enter="transition duration-160 ease-out"
         x-transition:enter-start="translate-y-2 opacity-0"
         class="absolute z-50 {{ $anchors[$placement] ?? $anchors['bottom'] }} {{ $width }} rounded-md border border-line bg-surface-raised p-1.5 shadow-lg">
        <ul class="text-sm text-ink">
            {{ $slot }}
        </ul>
    </div>
</div>
