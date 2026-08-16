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
                 x-transition:enter="transition duration-180 ease-out"
                 x-transition:enter-start="opacity-0"
                 x-on:click="hide()"
                 class="absolute inset-0 bg-[rgb(8_11_16/0.5)] backdrop-blur-[3px]"></div>

            <div x-ref="panel" tabindex="-1"
                 x-show="open"
                 x-transition:enter="transition duration-280 ease-rizz"
                 x-transition:enter-start="translate-x-full"
                 class="absolute inset-y-0 end-0 flex w-[94vw] {{ $width }} flex-col border-s border-line bg-surface-raised shadow-lg outline-none">

                <div class="flex items-start justify-between gap-4 border-b border-line px-[22px] py-5">
                    <h3 class="font-display text-[19px] font-semibold text-ink">{{ $title }}</h3>

                    <button type="button" x-on:click="hide()"
                            class="-me-1.5 inline-flex size-8 shrink-0 items-center justify-center rounded-sm border border-line-strong bg-[image:var(--mat-raised)] text-ink-muted shadow-[var(--bevel),var(--lift)] transition hover:brightness-95 active:translate-y-px active:shadow-press focus-visible:ring-3 focus-visible:ring-accent-soft focus-visible:outline-none">
                        <span class="sr-only">Tutup</span>
                        <x-ui.icon name="x" class="size-4" />
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-[22px] py-[22px] text-sm text-ink-secondary">
                    <template x-if="loading">
                        <div>
                            <x-ui.skeleton :lines="6" />
                        </div>
                    </template>

                    <template x-if="error">
                        <p class="flex items-center gap-2 text-sm2 text-danger" x-text="error"></p>
                    </template>

                    <div x-show="! loading && ! error" x-html="html"></div>
                </div>
            </div>
        </div>
    </template>
</div>
