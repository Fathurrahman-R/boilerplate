@props([
    'title' => 'Detail',
])

{{--
    Panel detail.

    Di layar lebar ia kolom ketiga yang berdiri di samping konten; di layar
    sempit ia lembar yang menutupinya. Bedanya bukan sekadar ukuran layar:
    panel yang berjalan berdampingan tidak memutus alur, jadi ia tidak
    meredupkan halaman dan tidak mengunci gulir. Meredupkan seluruh layar
    untuk sesuatu yang sebetulnya muat di sebelahnya memaksa orang menutupnya
    setiap kali ingin melihat konteksnya lagi.

    Satu panel melayani seluruh tabel di halaman: barisnya mengirim URL
    fragmennya lewat event, bukan menanam satu panel per baris di DOM.

        $dispatch('inspector-open', '{{ route('admin.users.panel', $user) }}')

    Fragmen yang dikembalikan server adalah HTML biasa tanpa layout. Kalau
    server menolak atau tidak menemukan, pesannya tampil di dalam panel —
    bukan halaman yang berpindah diam-diam.

    Dipasang sekali di <x-layouts.admin>, jadi halaman tidak perlu
    memasangnya sendiri.
--}}

<div x-data="inspector()"
     x-init="inspectorInit()"
     x-on:inspector-open.window="show($event.detail)"
     x-on:inspector-close.window="hide()"
     x-on:keydown.escape.window="open && ! wide && hide()"
     class="contents">

    {{-- Peredup hanya ada di mode sempit, tempat panelnya memang memblokir. --}}
    <div x-show="open && ! wide" x-cloak
         x-spring="{ from: { opacity: 0 }, to: { opacity: 1 }, token: 'overlay' }"
         x-on:click="hide()"
         class="fixed inset-0 z-[80] bg-scrim backdrop-blur-[var(--scrim-blur)] xl:hidden"
         aria-hidden="true"></div>

    <aside x-ref="panel" tabindex="-1"
           x-show="open" x-cloak
           x-spring="{ from: { opacity: 0, x: 24 }, to: { opacity: 1, x: 0 }, token: 'sheet' }"
           :aria-modal="! wide"
           role="dialog"
           aria-label="{{ $title }}"
           class="z-[85] flex flex-col outline-none
                  max-xl:fixed max-xl:inset-y-0 max-xl:end-0 max-xl:w-full max-xl:max-w-[420px]
                  xl:sticky xl:top-[calc(var(--shell-pad)+var(--safe-t))] xl:w-[var(--inspector-w)]
                  xl:h-[calc(100vh-2*var(--shell-pad))] xl:shrink-0">

        <div data-mat="thick" class="material flex h-full flex-col rounded-none xl:rounded-xl">
            <div class="flex shrink-0 items-center justify-between gap-3 px-4 py-3">
                <h2 class="vibrant truncate text-lg font-semibold">{{ $title }}</h2>

                <button type="button" x-on:click="hide()"
                        x-data="pressable()" x-bind="pressBind"
                        class="inline-flex size-8 shrink-0 items-center justify-center rounded-md bg-fill-3 text-ink-secondary focus-visible:outline-none">
                    <span class="sr-only">Tutup</span>
                    <x-ui.icon name="x" class="size-4" />
                </button>
            </div>

            <div x-ref="body"
                 x-data="scrollEdge()" x-init="init()"
                 class="scroll-edge flex-1 overflow-y-auto px-4 pb-4"
                 style="--_edge-bg: var(--surface-raised)">

                <template x-if="loading">
                    <div class="pt-1">
                        <x-ui.skeleton :lines="6" />
                    </div>
                </template>

                <template x-if="error">
                    <div class="pt-1">
                        <x-ui.alert variant="danger" x-text="error"></x-ui.alert>
                    </div>
                </template>

                <div x-show="! loading && ! error" x-html="html" class="text-base text-ink-secondary"></div>
            </div>
        </div>
    </aside>
</div>
