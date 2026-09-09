@props([
    'id',
    'title' => null,
    'size' => 'md',
])

{{--
    Modal hanya untuk hal yang butuh jawaban. Konfirmasi yang lewat begitu
    saja cukup pakai toast.

    Buka dari mana pun di halaman yang sama dengan memancarkan event:

        x-on:click="$dispatch('modal-open', 'hapus-user')"

    Esc menutup, fokus ditahan di dalam panel selama terbuka lalu kembali ke
    elemen pemicunya, dan halaman di belakang tidak ikut bergulir.

    Bisa ditarik ke bawah untuk ditutup. Selama ditarik, panel ikut jari,
    ukurannya menyusut sedikit, dan peredupnya memudar — ketiganya menerus,
    jadi gerakannya sudah menunjukkan hasilnya sebelum jari dilepas. Yang
    menentukan jadi tertutup atau kembali adalah ke mana gerakan itu menuju,
    bukan di mana jari kebetulan berhenti.
--}}

@php
    $sizes = [
        'sm' => 'max-w-[430px]',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
    ];
@endphp

<div x-data="sheet({ id: @js($id), axis: 'y' })"
     x-on:modal-open.window="matches($event.detail) && show()"
     x-on:modal-close.window="matches($event.detail) && hide()"
     x-on:keydown.escape.window="open && hide()">

    <template x-teleport="body">
        <div x-show="open" x-cloak
             class="fixed inset-0 z-[70] flex items-end justify-center overflow-y-auto p-4 sm:items-center sm:p-6"
             role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title">

            <div x-ref="scrim"
                 x-show="open"
                 x-spring="{ from: { opacity: 0 }, to: { opacity: 1 }, token: 'overlay' }"
                 x-on:click="hide()"
                 class="fixed inset-0 bg-scrim backdrop-blur-[var(--scrim-blur)]"></div>

            <div x-ref="panel" tabindex="-1"
                 x-show="open"
                 x-spring="{ from: { opacity: 0, y: 24, scale: 0.96, filter: 'blur(10px)' },
                             to:   { opacity: 1, y: 0,  scale: 1,    filter: 'blur(0px)' },
                             token: 'sheet' }"
                 data-mat="thick"
                 class="material relative w-full {{ $sizes[$size] ?? $sizes['md'] }} overflow-hidden rounded-2xl outline-none">

                {{-- Pegangan: bidang tarik yang terlihat, plus seluruh area
                     header. Tanpa penanda, tidak ada yang tahu panel ini bisa
                     ditarik. --}}
                <div x-ref="grabber" x-init="startDrag($el)"
                     class="flex cursor-grab touch-none flex-col items-center pt-2.5 active:cursor-grabbing">
                    <span class="h-[5px] w-9 rounded-full bg-fill-1" aria-hidden="true"></span>

                    <div class="flex w-full items-start justify-between gap-4 px-5 pt-3 pb-3">
                        <h3 id="{{ $id }}-title" class="vibrant text-lg font-semibold">{{ $title }}</h3>

                        <button type="button" x-on:click="hide()" x-on:pointerdown.stop
                                x-data="pressable()" x-bind="pressBind"
                                class="-me-1 inline-flex size-8 shrink-0 items-center justify-center rounded-md bg-fill-3 text-ink-secondary focus-visible:outline-none">
                            <span class="sr-only">Tutup</span>
                            <x-ui.icon name="x" class="size-4" />
                        </button>
                    </div>
                </div>

                <div x-data="scrollEdge()" x-init="init()"
                     class="scroll-edge max-h-[70vh] overflow-y-auto px-5 pb-5 text-base text-ink-secondary">
                    <div class="flex flex-col gap-4">
                        {{ $slot }}
                    </div>
                </div>

                @isset($footer)
                    <div class="flex flex-wrap items-center justify-end gap-2.5 px-5 py-4">
                        {{ $footer }}
                    </div>
                @endisset
            </div>
        </div>
    </template>
</div>
