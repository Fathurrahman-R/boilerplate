@props([
    'title' => 'Hapus data',
])

{{--
    Satu dialog konfirmasi hapus untuk seluruh tabel di halaman.

    Sebelumnya tiap baris menanam modalnya sendiri di DOM: halaman berisi 25
    baris berarti 25 dialog lengkap yang menunggu, padahal paling banyak satu
    yang akan dibuka. Sekarang barisnya cukup mengirim URL dan namanya lewat
    event.

        x-on:click="$dispatch('confirm-delete', { url: '…', name: 'Budi' })"
--}}

<div x-data="confirmDelete()"
     x-on:confirm-delete.window="ask($event.detail)"
     x-on:keydown.escape.window="open && hide()">

    <template x-teleport="body">
        <div x-show="open" x-cloak
             class="fixed inset-0 z-[75] flex items-end justify-center overflow-y-auto p-4 sm:items-center sm:p-6"
             role="dialog" aria-modal="true" aria-labelledby="confirm-delete-title">

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
                 class="material relative w-full max-w-[430px] overflow-hidden rounded-2xl outline-none">

                <div x-ref="grabber" x-init="startDrag($el)"
                     class="flex cursor-grab touch-none flex-col items-center pt-2.5 active:cursor-grabbing">
                    <span class="h-[5px] w-9 rounded-full bg-fill-1" aria-hidden="true"></span>

                    <h3 id="confirm-delete-title" class="vibrant w-full px-5 pt-3 text-lg font-semibold">
                        {{ $title }}
                    </h3>
                </div>

                <p class="px-5 pt-1 text-base text-ink-secondary">
                    Yakin menghapus <strong class="font-semibold text-ink" x-text="name"></strong>?
                    Tindakan ini tidak bisa dibatalkan.
                </p>

                <p x-show="note" x-cloak class="px-5 pt-1.5 text-base text-ink-muted" x-text="note"></p>

                <div class="h-5"></div>

                <div class="flex flex-wrap items-center justify-end gap-2.5 px-5 pb-5">
                    <x-ui.button variant="secondary" type="button" x-on:click="hide()">Batal</x-ui.button>

                    <form method="POST" :action="url">
                        @csrf
                        @method('DELETE')
                        <x-ui.button variant="danger" type="submit">Hapus</x-ui.button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
