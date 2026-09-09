{{--
    Pencarian global lintas menu, dibuka dengan ⌘K (Ctrl+K di Windows/Linux).

    Sumbernya NavigationBuilder yang sama dengan sidebar, jadi daftarnya sudah
    tersaring resource key milik pengguna — tidak ada jalan pintas ke halaman
    yang tidak boleh dibuka.
--}}

@php
    $commands = collect(app(App\Support\Navigation\NavigationBuilder::class)->build())
        ->flatMap(function (array $item): array {
            if ($item['children'] === []) {
                return [[
                    'label' => $item['label'],
                    'group' => 'Menu',
                    'url' => $item['url'],
                ]];
            }

            return collect($item['children'])
                ->map(fn (array $child): array => [
                    'label' => $child['label'],
                    'group' => $item['label'],
                    'url' => $child['url'],
                ])
                ->all();
        })
        ->filter(fn (array $command): bool => filled($command['url']))
        ->values();
@endphp

<div x-data="{
        commands: @js($commands),
        open: false,
        query: '',
        active: 0,
        get results() {
            const q = this.query.trim().toLowerCase();
            if (q === '') return this.commands;
            return this.commands.filter(c => (c.label + ' ' + c.group).toLowerCase().includes(q));
        },
        show() {
            this.open = true;
            this.query = '';
            this.active = 0;
            this.$nextTick(() => this.$refs.search?.focus());
        },
        move(by) {
            const count = this.results.length;
            if (count === 0) return;
            this.active = (this.active + by + count) % count;
        },
        go() {
            const hit = this.results[this.active];
            if (hit) window.location.href = hit.url;
        },
     }"
     x-on:command-palette-open.window="show()"
     x-on:keydown.window.prevent.cmd.k="show()"
     x-on:keydown.window.prevent.ctrl.k="show()"
     x-on:keydown.escape.window="open = false">

    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-[80] flex items-start justify-center p-6 pt-[12vh]"
             role="dialog" aria-modal="true" aria-label="Cari menu">

            <div x-show="open"
                 x-spring="{ from: { opacity: 0 }, to: { opacity: 1 }, token: 'overlay' }"
                 x-on:click="open = false"
                 class="absolute inset-0 bg-scrim backdrop-blur-[var(--scrim-blur)]"></div>

            <div x-show="open"
                 x-spring="{ from: { opacity: 0, y: 16, scale: 0.96, filter: 'blur(10px)' },
                             to:   { opacity: 1, y: 0,  scale: 1,    filter: 'blur(0px)' },
                             token: 'sheet', origin: 'top center' }"
                 data-mat="thick"
                 class="material relative w-full max-w-lg overflow-hidden rounded-2xl">

                <div class="flex items-center gap-3 px-4">
                    <x-ui.icon name="search" class="size-4 shrink-0 text-ink-muted" />

                    <input type="text" x-ref="search" x-model="query"
                           x-on:input="active = 0"
                           x-on:keydown.arrow-down.prevent="move(1)"
                           x-on:keydown.arrow-up.prevent="move(-1)"
                           x-on:keydown.enter.prevent="go()"
                           placeholder="Cari halaman…"
                           class="h-12 flex-1 border-0 bg-transparent text-body text-ink outline-none placeholder:text-ink-quaternary">

                    <kbd class="rounded-sm bg-fill-3 px-1.5 py-0.5 text-2xs text-ink-muted">Esc</kbd>
                </div>

                <div x-data="scrollEdge()" x-init="init()"
                     class="scroll-edge max-h-80 overflow-y-auto p-1.5"
                     style="--_edge-bg: var(--surface-raised)">
                    <template x-for="(command, index) in results" :key="command.url">
                        <a :href="command.url"
                           x-on:mouseenter="active = index"
                           class="flex items-center gap-2.5 rounded-md px-2.5 py-2.5 text-base transition-colors duration-[--dur-fast]"
                           :class="index === active ? 'bg-fill-3 text-ink' : 'text-ink-secondary'">
                            <span class="flex-1" x-text="command.label"></span>
                            <span class="text-xs text-ink-quaternary" x-text="command.group"></span>
                        </a>
                    </template>

                    <p x-show="results.length === 0" class="px-2.5 py-3 text-base text-ink-muted">
                        Tidak ada menu yang cocok.
                    </p>
                </div>
            </div>
        </div>
    </template>
</div>
