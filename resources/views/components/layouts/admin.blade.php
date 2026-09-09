@props([
    'title' => null,
    'heading' => null,
    'description' => null,
    // Dipertahankan supaya 45 halaman pemanggil tidak perlu diubah. Sidebar
    // sudah menandai lokasi dan judul halaman mengulanginya; jejak ketiga di
    // pita permanen tidak menambah apa pun. Halaman yang memang bertingkat
    // tiga masih bisa memasang komponen breadcrumb sendiri di dalam kontennya.
    'breadcrumb' => [],
])

{{--
    Shell aplikasi: tiga kolom.

        [ sidebar ] [ toolbar + judul + konten ] [ panel detail ]

    Satu pita chrome, bukan tiga. Judulnya mulai besar di dalam konten lalu
    menciut ke toolbar saat digulir, jadi ia tetap terbaca setelah tergulir
    pergi. Aksi utama tinggal di ujung toolbar — di situlah tangan sudah
    berada, dan tempatnya tidak berpindah dari halaman ke halaman.

    Kolom kontennya berhenti melebar di --page-max. Tanpa batas itu, tabel dan
    paragraf melar sampai ujung monitor dan mata kehilangan awal baris
    berikutnya.
--}}

<x-layouts.base :title="$title ?? $heading" backdrop="shell">
    <div class="relative flex min-h-screen items-start gap-[var(--shell-gap)]"
         style="padding: calc(var(--shell-pad) + var(--safe-t)) calc(var(--shell-pad) + var(--safe-r)) calc(var(--shell-pad) + var(--safe-b)) calc(var(--shell-pad) + var(--safe-l))">

        @include('layouts.partials.sidebar')

        <main x-data="largeTitle()" x-init="titleInit()"
              class="flex min-w-0 flex-1 flex-col gap-[var(--shell-gap)]">

            <x-partials.toolbar :heading="$heading" :actions="$actions ?? null" />

            <div class="flex w-full max-w-[var(--page-max)] flex-col gap-4">
                @if ($heading || $description)
                    <x-ui.page-header :heading="$heading" :description="$description" />
                @endif

                {{ $slot }}
            </div>
        </main>

        <x-ui.inspector />
    </div>

    <x-ui.command-palette />
</x-layouts.base>
