@props([
    'id',
    'title' => null,
    'side' => 'end',
    'width' => 'max-w-md',
])

{{--
    Panel samping untuk tugas sampingan yang butuh konteks halaman tetap
    terlihat. Kalau isinya sudah sepenuhnya berdiri sendiri, itu halaman.

    Buka dengan event, sama seperti modal:

        x-on:click="$dispatch('drawer-open', 'filter')"

    `side`: 'end' (kanan, bawaan), 'start' (kiri).

    Masuk dan keluar lewat jalur yang sama: yang datang dari kanan pergi ke
    kanan. Satu pasang nilai menggerakkan kedua arah, jadi tidak mungkin
    keduanya berbeda. Bisa ditarik untuk ditutup, dengan perlawanan yang
    meningkat kalau ditarik melewati posisi terbukanya.
--}}

@php
    $isStart = $side === 'start';
    $offset = $isStart ? -420 : 420;
@endphp

<div x-data="sheet({ id: @js($id), axis: 'x', side: @js($side) })"
     x-on:drawer-open.window="matches($event.detail) && show()"
     x-on:drawer-close.window="matches($event.detail) && hide()"
     x-on:keydown.escape.window="open && hide()">

    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-[70]" role="dialog" aria-modal="true"
             aria-labelledby="{{ $id }}-title">

            <div x-ref="scrim"
                 x-show="open"
                 x-spring="{ from: { opacity: 0 }, to: { opacity: 1 }, token: 'overlay' }"
                 x-on:click="hide()"
                 class="absolute inset-0 bg-scrim backdrop-blur-[var(--scrim-blur)]"></div>

            <div x-ref="panel" tabindex="-1"
                 x-show="open"
                 x-spring="{ from: { opacity: 0, x: {{ $offset }} },
                             to:   { opacity: 1, x: 0 },
                             token: 'sheet' }"
                 data-mat="thick"
                 class="material absolute inset-y-0 {{ $isStart ? 'start-0 rounded-e-2xl' : 'end-0 rounded-s-2xl' }} flex w-full {{ $width }} flex-col outline-none">

                {{-- Strip pegangan di tepi dalam panel: tempat jari mulai
                     menarik, tanpa mengganggu isi yang bisa digulir. --}}
                <div x-ref="grabber" x-init="startDrag($el)"
                     class="absolute inset-y-0 {{ $isStart ? 'end-0' : 'start-0' }} w-5 cursor-grab touch-none active:cursor-grabbing"
                     aria-hidden="true"></div>

                <div class="flex shrink-0 items-start justify-between gap-4 px-5 py-4">
                    <h3 id="{{ $id }}-title" class="vibrant text-lg font-semibold">{{ $title }}</h3>

                    <button type="button" x-on:click="hide()"
                            x-data="pressable()" x-bind="pressBind"
                            class="-me-1 inline-flex size-8 shrink-0 items-center justify-center rounded-md bg-fill-3 text-ink-secondary focus-visible:outline-none">
                        <span class="sr-only">Tutup</span>
                        <x-ui.icon name="x" class="size-4" />
                    </button>
                </div>

                <div x-data="scrollEdge()" x-init="init()"
                     class="scroll-edge flex-1 overflow-y-auto px-5 pb-5 text-base text-ink-secondary">
                    {{ $slot }}
                </div>

                @isset($footer)
                    <div class="flex shrink-0 flex-wrap items-center justify-end gap-2.5 px-5 py-4">
                        {{ $footer }}
                    </div>
                @endisset
            </div>
        </div>
    </template>
</div>
