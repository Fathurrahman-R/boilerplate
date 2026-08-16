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
                 x-transition:enter="transition duration-180 ease-out"
                 x-transition:enter-start="opacity-0"
                 x-on:click="open = false"
                 class="absolute inset-0 bg-[rgb(8_11_16/0.55)] backdrop-blur-[3px]"></div>

            <div x-show="open"
                 x-transition:enter="transition duration-240 ease-rizz"
                 x-transition:enter-start="translate-y-2.5 scale-[0.98] opacity-0"
                 class="relative w-full max-w-lg overflow-hidden rounded-xl border border-line bg-surface-raised shadow-lg">

                <div class="flex items-center gap-3 border-b border-line px-4">
                    <x-ui.icon name="search" class="size-4 shrink-0 text-ink-muted" />

                    <input type="text" x-ref="search" x-model="query"
                           x-on:input="active = 0"
                           x-on:keydown.arrow-down.prevent="move(1)"
                           x-on:keydown.arrow-up.prevent="move(-1)"
                           x-on:keydown.enter.prevent="go()"
                           placeholder="Cari halaman…"
                           class="h-12 flex-1 border-0 bg-transparent text-sm text-ink outline-none placeholder:text-ink-muted">

                    <kbd class="rounded-[4px] bg-surface-sunken px-1.5 py-0.5 font-mono text-[11px] text-ink-muted shadow-well">Esc</kbd>
                </div>

                <div class="max-h-80 overflow-y-auto p-1.5">
                    <template x-for="(command, index) in results" :key="command.url">
                        <a :href="command.url"
                           x-on:mouseenter="active = index"
                           class="flex items-center gap-2.5 rounded-sm px-2.5 py-2.5 text-sm transition"
                           :class="index === active ? 'bg-surface-inset text-ink' : 'text-ink-secondary'">
                            <span class="flex-1" x-text="command.label"></span>
                            <span class="font-mono text-[11px] text-ink-muted" x-text="command.group"></span>
                        </a>
                    </template>

                    <p x-show="results.length === 0" class="px-2.5 py-3 text-[13.5px] text-ink-muted">
                        Tidak ada menu yang cocok.
                    </p>
                </div>
            </div>
        </div>
    </template>
</div>
