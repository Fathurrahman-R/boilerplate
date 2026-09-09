@props([
    'title' => 'Detail',
    'width' => 'max-w-[440px]',
])

{{--
    Panel detail yang isinya diambil saat dibuka.

    Satu komponen ini melayani seluruh tabel di halaman: baris mengirim URL
    fragmennya lewat event, bukan menanam satu drawer per baris di DOM.

        $dispatch('drawer-remote-open', '{{ route('admin.users.panel', $user) }}')

    Fragmen yang dikembalikan server adalah HTML biasa tanpa layout. Kalau
    server menolak (403) atau tidak menemukan (404), pesannya tampil di dalam
    panel — bukan halaman yang berpindah diam-diam.
--}}

<div x-data="{
        open: false,
        loading: false,
        html: '',
        error: '',
        trigger: null,
        async show(url) {
            this.trigger = document.activeElement;
            this.open = true;
            this.loading = true;
            this.error = '';
            this.html = '';
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => this.$refs.panel?.focus());

            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });

                if (! response.ok) {
                    this.error = response.status === 403
                        ? 'Anda tidak punya akses ke data ini.'
                        : 'Detail tidak bisa dimuat (' + response.status + ').';
                } else {
                    this.html = await response.text();
                }
            } catch (e) {
                this.error = 'Detail tidak bisa dimuat. Periksa koneksi lalu coba lagi.';
            } finally {
                this.loading = false;
            }
        },
        hide() {
            this.open = false;
            document.body.style.overflow = '';
            this.trigger?.focus();
        },
     }"
     x-on:drawer-remote-open.window="show($event.detail)"
     x-on:drawer-remote-close.window="hide()"
     x-on:keydown.escape.window="open && hide()">

    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-[85]" role="dialog" aria-modal="true"
             aria-label="{{ $title }}">

            <div x-show="open"
                 x-spring="{ from: { opacity: 0 }, to: { opacity: 1 }, token: 'overlay' }"
                 x-on:click="hide()"
                 class="absolute inset-0 bg-scrim backdrop-blur-[var(--scrim-blur)]"></div>

            <div x-ref="panel" tabindex="-1"
                 x-show="open"
                 x-spring="{ from: { opacity: 0, x: 420 }, to: { opacity: 1, x: 0 }, token: 'sheet' }"
                 data-mat="thick"
                 class="material absolute inset-y-0 end-0 flex w-[94vw] {{ $width }} flex-col rounded-s-2xl outline-none">

                <div class="flex shrink-0 items-start justify-between gap-4 px-5 py-4">
                    <h3 class="vibrant text-lg font-semibold">{{ $title }}</h3>

                    <button type="button" x-on:click="hide()"
                            x-data="pressable()" x-bind="pressBind"
                            class="-me-1 inline-flex size-8 shrink-0 items-center justify-center rounded-md bg-fill-3 text-ink-secondary outline-none focus-visible:shadow-[var(--focus-ring)]">
                        <span class="sr-only">Tutup</span>
                        <x-ui.icon name="x" class="size-4" />
                    </button>
                </div>

                <div x-data="scrollEdge()" x-init="init()"
                     class="scroll-edge flex-1 overflow-y-auto px-5 pb-5 text-base text-ink-secondary">
                    <template x-if="loading">
                        <div>
                            <x-ui.skeleton :lines="6" />
                        </div>
                    </template>

                    <template x-if="error">
                        <p class="flex items-center gap-2 text-base text-danger" x-text="error"></p>
                    </template>

                    <div x-show="! loading && ! error" x-html="html"></div>
                </div>
            </div>
        </div>
    </template>
</div>
