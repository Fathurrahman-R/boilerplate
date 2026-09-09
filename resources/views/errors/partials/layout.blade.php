@php
    $hint ??= null;
@endphp

{{--
    Halaman galat.

    Angka kodenya turun jadi keterangan kecil, bukan angka raksasa yang jadi
    hal pertama terbaca. "404" tidak memberi tahu siapa pun apa yang terjadi
    atau apa yang harus dilakukan; kalimat penjelasnya yang melakukan itu, jadi
    kalimat itulah yang naik ke atas.

    Satu aksi utama, satu jalan kembali. Halaman yang buntu tetap harus
    menjawab "bagaimana saya keluar dari sini".
--}}

<x-layouts.base :title="$title">
    <div class="bg-glow flex min-h-screen flex-col items-center justify-center px-5"
         style="padding-block: calc(2.5rem + var(--safe-t)) calc(2.5rem + var(--safe-b))">

        <div class="flex w-full max-w-[440px] flex-col items-center text-center">
            <span class="eyebrow">Galat {{ $code }}</span>

            <h1 class="mt-3 text-3xl font-semibold text-ink">{{ $title }}</h1>

            <p class="mt-2.5 text-body text-ink-secondary">{{ $message }}</p>

            @if ($hint)
                <p class="mt-2 text-base text-ink-muted">{{ $hint }}</p>
            @endif

            <div class="mt-7 flex flex-wrap items-center justify-center gap-2">
                @auth
                    <x-ui.button :href="route('dashboard')">Ke dashboard</x-ui.button>
                @else
                    <x-ui.button :href="url('/')">Ke beranda</x-ui.button>
                @endauth

                <x-ui.button :href="url()->previous()" variant="ghost">Kembali</x-ui.button>
            </div>
        </div>
    </div>
</x-layouts.base>
