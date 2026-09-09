@props([
    'heading' => null,
    'actions' => null,
])

{{--
    Toolbar aplikasi.

    Menggantikan topbar berisi breadcrumb plus baris judul yang dulu berdiri
    terpisah di bawahnya. Yang tersisa satu pita: tombol menu, judul yang
    muncul saat judul besarnya tergulir pergi, pencarian, notifikasi, tema,
    akun — dan aksi utama halaman di ujung belakang.

    Ref `compact` dibaca largeTitle() di <x-layouts.admin>: judul kecil di
    sini dan judul besar di bawahnya bergerak berlawanan arah dan saling
    menyilang, jadi yang terbaca satu judul yang berpindah tempat, bukan dua
    judul yang muncul bergantian.
--}}

<x-ui.toolbar>
    <x-slot:leading>
        <button type="button"
                x-on:click="$store.shell.toggleSidebar()"
                x-data="pressable()" x-bind="pressBind"
                class="inline-flex size-9 shrink-0 items-center justify-center rounded-md text-ink-secondary focus-visible:outline-none lg:hidden">
            <span class="sr-only">Buka menu</span>
            <x-ui.icon name="menu" class="size-5" />
        </button>
    </x-slot:leading>

    <x-slot:title>
        @if ($heading)
            <h2 x-ref="compact" style="opacity: 0"
                class="vibrant truncate text-lg font-semibold">
                {{ $heading }}
            </h2>
        @endif
    </x-slot:title>

    <x-slot:actions>
        @if ($actions)
            <div class="me-1 flex items-center gap-1.5">{{ $actions }}</div>
        @endif

        {{-- Pencarian tampil sebagai kolom di layar lebar dan menyusut jadi
             tombol ikon begitu ruangnya sempit. --}}
        <button type="button"
                x-on:click="$dispatch('command-palette-open')"
                class="hidden h-9 min-w-[180px] items-center gap-2.5 rounded-md bg-fill-4 ps-3 pe-2 text-base text-ink-muted transition-colors duration-[--dur-fast] hover:bg-fill-3 focus-visible:outline-none lg:flex">
            <x-ui.icon name="search" class="size-4" />
            <span class="flex-1 text-start">Cari…</span>
            <x-ui.kbd variant="well">⌘K</x-ui.kbd>
        </button>

        <button type="button"
                x-on:click="$dispatch('command-palette-open')"
                x-data="pressable()" x-bind="pressBind"
                class="inline-flex size-9 items-center justify-center rounded-md bg-fill-3 text-ink-secondary focus-visible:outline-none lg:hidden">
            <span class="sr-only">Cari halaman</span>
            <x-ui.icon name="search" class="size-[17px]" />
        </button>

        <x-ui.notification-menu />

        <button type="button" data-theme-toggle
                x-data="pressable()" x-bind="pressBind"
                class="inline-flex size-9 items-center justify-center rounded-md bg-fill-3 text-ink-secondary focus-visible:outline-none"
                title="Ganti tema">
            <span class="sr-only">Ganti tema</span>
            <x-ui.icon name="sun-moon" class="size-[17px]" />
        </button>

        <x-ui.dropdown id="user-menu" placement="bottom-end">
            <x-slot:trigger>
                <x-ui.avatar :user="auth()->user()" size="sm" />
            </x-slot:trigger>

            <li class="px-2.5 pt-1 pb-2.5" role="none">
                <div class="truncate text-base font-semibold text-ink">{{ auth()->user()->name }}</div>
                <div class="truncate text-sm text-ink-muted">{{ auth()->user()->email }}</div>
            </li>

            <x-ui.dropdown-item :href="route('profile.edit')">
                <x-ui.icon name="user" />
                Profil saya
            </x-ui.dropdown-item>

            <li role="none">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" role="menuitem" tabindex="-1"
                            class="flex w-full items-center gap-2.5 rounded-sm px-2.5 py-2 text-left text-base text-danger transition-colors duration-[--dur-fast] hover:bg-danger-soft">
                        <x-ui.icon name="log-out" class="size-4" />
                        Keluar
                    </button>
                </form>
            </li>
        </x-ui.dropdown>
    </x-slot:actions>
</x-ui.toolbar>
