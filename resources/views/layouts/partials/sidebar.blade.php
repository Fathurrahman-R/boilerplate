@php($navigation = app(App\Support\Navigation\NavigationBuilder::class)->build())

{{--
    Sidebar: daftar sumber.

    Panel material paling tebal di aplikasi — bukan karena ingin menonjol,
    tapi karena bobot material menyampaikan hierarki: ini wilayah struktural,
    bukan kontrol yang mengambang di atas konten.

    Lebar dan penyembunyian label dikendalikan CSS lewat `data-sidebar` di
    <html> (lihat app.css). Alpine hanya membalik nilainya, jadi tidak ada
    lompatan saat halaman pertama kali digambar.

    Di bawah 1024px panel ini jadi drawer yang bisa diseret: ditarik untuk
    ditutup, disapu dari tepi layar untuk dibuka, dan bisa ditangkap di tengah
    gerakan lalu dibalik. Posisinya ditulis spring, bukan dibalik antara dua
    kelas — itulah yang membuat ada jalan menerus di antara terbuka dan
    tertutup.
--}}

<div x-data="mobileSidebar()" x-init="sidebarInit()" class="contents">

    {{-- Strip tipis di tepi layar: tempat memulai sapuan untuk membuka. --}}
    <div x-ref="edge" class="fixed inset-y-0 start-0 z-40 w-5 lg:hidden" aria-hidden="true"></div>

    <div x-ref="scrim"
         x-show="$store.shell.sidebarOpen" x-cloak
         x-on:click="$store.shell.closeSidebar()"
         class="fixed inset-0 z-40 bg-scrim lg:hidden"
         style="opacity: 0"
         aria-hidden="true"></div>

    <aside x-ref="panel"
           x-on:keydown.escape.window="$store.shell.closeSidebar()"
           class="fixed inset-y-0 start-0 z-50 flex w-[280px] flex-col
                  lg:sticky lg:top-[calc(var(--shell-pad)+var(--safe-t))] lg:z-auto
                  lg:h-[calc(100vh-2*var(--shell-pad))] lg:w-[var(--shell-sidebar)] lg:shrink-0"
           aria-label="Menu utama">

        <div data-mat="chrome" class="material relative flex h-full flex-col rounded-none px-2.5 py-3 lg:rounded-xl">
            {{-- Tombol ciut hanya masuk akal di layar lebar; di layar sempit
                 panelnya memang menutup penuh. --}}
            <button type="button"
                    x-on:click="$store.shell.toggleCollapsed()"
                    x-data="pressable()" x-bind="pressBind"
                    :title="$store.shell.collapsed ? 'Lebarkan menu' : 'Ciutkan menu'"
                    class="absolute end-[-13px] top-5 z-10 hidden size-[26px] items-center justify-center rounded-full bg-surface-raised text-ink-secondary shadow-md focus-visible:outline-none lg:flex">
                <span class="sr-only">Ciutkan menu</span>
                <span class="flex transition-transform duration-[--dur-base] ease-[var(--ease-out-apple)]" data-rail="flip">
                    <x-ui.icon name="chevron-left" class="size-3.5" />
                </span>
            </button>

            <a href="{{ route('dashboard') }}" data-rail="center"
               class="mb-4 flex items-center gap-2.5 overflow-hidden px-2 py-1 whitespace-nowrap">
                <span class="flex size-7 shrink-0 items-center justify-center rounded-md bg-accent text-base font-bold text-accent-on">
                    {{ mb_substr(config('app.name'), 0, 1) }}
                </span>
                <span class="min-w-0 flex-1" data-rail="hide">
                    <span class="vibrant block truncate text-base font-semibold">
                        {{ config('app.name') }}
                    </span>
                    <span class="block truncate text-xs text-ink-muted">
                        {{ app()->isProduction() ? 'Workspace produksi' : 'Workspace '.app()->environment() }}
                    </span>
                </span>
            </a>

            <nav class="flex flex-1 flex-col gap-4 overflow-x-hidden overflow-y-auto">
                {{-- Item bertingkat jadi seksi bertajuk, bukan disclosure
                     ber-chevron: strukturnya terbaca sekaligus alih-alih
                     harus dibuka satu per satu untuk tahu isinya. --}}
                @foreach ($navigation as $item)
                    @if ($item['children'] === [])
                        <div class="flex flex-col gap-0.5">
                            <x-partials.nav-link :item="$item" />
                        </div>
                    @else
                        <div class="flex flex-col gap-0.5">
                            <div class="eyebrow px-2.5 pb-1" data-rail="hide">{{ $item['label'] }}</div>

                            {{-- Saat rail, tajuk seksi hilang dan ikon induknya
                                 mengambil alih supaya kelompoknya tetap
                                 terbaca. --}}
                            @if ($item['icon'])
                                <div class="hidden justify-center py-1 text-ink-quaternary"
                                     title="{{ $item['label'] }}"
                                     data-rail="show">
                                    <x-ui.icon :name="$item['icon']" class="size-4" />
                                </div>
                            @endif

                            @foreach ($item['children'] as $child)
                                <x-partials.nav-link :item="$child" />
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </nav>

            <div class="mt-2 flex flex-col gap-0.5">
                @if (config('design-system.enabled'))
                    <x-partials.nav-link :item="[
                        'label' => 'Design system',
                        'url' => route('design-system.foundation'),
                        'icon' => 'swatch-book',
                        'active' => request()->routeIs('design-system.*'),
                        'badge' => null,
                    ]" />
                @endif

                <a href="{{ route('profile.edit') }}"
                   title="{{ auth()->user()->name }}"
                   data-rail="center"
                   class="flex items-center gap-2.5 overflow-hidden rounded-md px-2 py-1.5 whitespace-nowrap transition-colors duration-[--dur-fast] hover:bg-fill-3">
                    <x-ui.avatar :user="auth()->user()" size="xs2" />
                    <span class="min-w-0 flex-1" data-rail="hide">
                        <span class="block truncate text-base font-medium text-ink">{{ auth()->user()->name }}</span>
                        <span class="block truncate text-xs text-ink-muted">{{ auth()->user()->email }}</span>
                    </span>
                </a>
            </div>
        </div>
    </aside>
</div>
