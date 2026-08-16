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

        <x-ui.button x-on:click="$dispatch('drawer-open', 'filter')">Filter</x-ui.button>

    `side`: 'end' (kanan, bawaan), 'start' (kiri).
--}}

@php
    $isStart = $side === 'start';
@endphp

<div x-data="{
        open: false,
        trigger: null,
        show() {
            this.trigger = document.activeElement;
            this.open = true;
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => this.$refs.panel?.focus());
        },
        hide() {
            this.open = false;
            document.body.style.overflow = '';
            this.trigger?.focus();
        },
     }"
     x-on:drawer-open.window="$event.detail === '{{ $id }}' && show()"
     x-on:drawer-close.window="$event.detail === '{{ $id }}' && hide()"
     x-on:keydown.escape.window="open && hide()">

    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-[70]" role="dialog" aria-modal="true"
             aria-labelledby="{{ $id }}-title">

            <div x-show="open"
                 x-transition:enter="transition duration-180 ease-out"
                 x-transition:enter-start="opacity-0"
                 x-on:click="hide()"
                 class="absolute inset-0 bg-[rgb(8_11_16/0.5)]"></div>

            <div x-ref="panel" tabindex="-1"
                 x-show="open"
                 x-transition:enter="transition duration-280 ease-rizz"
                 x-transition:enter-start="{{ $isStart ? '-translate-x-full' : 'translate-x-full' }}"
                 class="absolute inset-y-0 {{ $isStart ? 'start-0 border-e' : 'end-0 border-s' }} flex w-full {{ $width }} flex-col border-line bg-surface-raised shadow-lg outline-none">

                <div class="flex items-start justify-between gap-4 border-b border-line px-5 py-4">
                    <h3 id="{{ $id }}-title" class="font-display text-base font-semibold text-ink">{{ $title }}</h3>

                    <button type="button" x-on:click="hide()"
                            class="-me-1.5 inline-flex size-8 shrink-0 items-center justify-center rounded-sm text-ink-muted transition hover:bg-surface-inset hover:text-ink focus-visible:outline-none focus-visible:ring-3 focus-visible:ring-accent-soft">
                        <span class="sr-only">Tutup</span>
                        <x-ui.icon name="x" class="size-4" />
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-5 py-5 text-sm text-ink-secondary">
                    {{ $slot }}
                </div>

                @isset($footer)
                    <div class="flex flex-wrap items-center justify-end gap-2.5 border-t border-line px-5 py-4">
                        {{ $footer }}
                    </div>
                @endisset
            </div>
        </div>
    </template>
</div>
