@props([
    // Judul kecil di atas grup.
    'title' => null,
    // Teks bantuan di bawah grup. Di sinilah penjelasan tinggal — bukan di
    // bawah tiap field, yang membuat form jadi dua kali lebih panjang dan
    // penjelasannya terbaca berulang-ulang.
    'hint' => null,
    // Grup duduk di permukaan halaman dengan jarak ke tepi. Setel false untuk
    // grup yang sudah berada di dalam kartu atau panel.
    'inset' => true,
])

{{--
    Grup daftar.

    Bentuk dasar untuk pengaturan, form, dan daftar detail. Satu grup adalah
    satu gagasan: judulnya menyebut apa yang diatur, barisnya mengaturnya, dan
    teks di bawahnya menjelaskan akibatnya.

    Pemisah antar-baris tidak menyentuh tepi kiri — ia mulai dari tempat isi
    barisnya mulai. Garis yang menyentuh kedua tepi membelah kartunya jadi
    potongan-potongan terpisah; garis yang menjorok membacanya tetap satu
    benda dengan beberapa baris di dalamnya.

        x-ui.list title="Profil" hint="…"
          berisi beberapa x-ui.form-row
--}}

<div {{ $attributes->class(['flex flex-col', $inset ? 'gap-2' : 'gap-1.5']) }}>
    @if ($title)
        <h3 class="px-4 text-sm font-semibold text-ink-secondary">{{ $title }}</h3>
    @endif

    <div class="overflow-hidden rounded-lg border-[0.5px] border-line bg-surface-raised">
        {{ $slot }}
    </div>

    @if ($hint)
        <p class="px-4 text-sm text-ink-muted">{{ $hint }}</p>
    @endif
</div>
