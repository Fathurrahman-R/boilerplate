@props([
    'mat' => 'regular',
    // Melekat di atas saat digulir. Dimatikan untuk toolbar di dalam panel.
    'sticky' => true,
])

{{--
    Toolbar.

    Satu pita, bukan tiga. Sebelumnya sebuah halaman menumpuk topbar berisi
    breadcrumb, lalu baris judul berisi judul dan deskripsi dan tombol, lalu
    header kartu — tiga lapis chrome sebelum satu data pun terlihat, dan
    ketiganya menjawab pertanyaan yang sama.

    Sekarang: penanda navigasi di depan, judul yang menciut di tengah, aksi
    utama di ujung belakang. Aksi tinggal di sini karena di sinilah tangan
    sudah berada, dan karena posisinya tidak berubah dari halaman ke halaman.

        x-ui.toolbar dengan slot `leading`, `title`, dan `actions`
--}}

<header
    data-mat="{{ $mat }}"
    {{ $attributes->class([
        'material z-30 flex h-toolbar shrink-0 items-center gap-2 rounded-xl px-3',
        'sticky top-[calc(var(--shell-pad)+var(--safe-t))]' => $sticky,
    ]) }}>

    @isset($leading)
        <div class="flex shrink-0 items-center gap-1">{{ $leading }}</div>
    @endisset

    {{-- Judul mengambil ruang sisa dan memotong dirinya sendiri, supaya aksi
         di ujung tidak pernah terdorong keluar layar oleh judul yang panjang. --}}
    <div class="min-w-0 flex-1">
        {{ $title ?? '' }}
    </div>

    @isset($actions)
        <div class="flex shrink-0 items-center gap-1.5">{{ $actions }}</div>
    @endisset

    {{ $slot }}
</header>
