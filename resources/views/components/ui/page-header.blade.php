@props([
    'heading' => null,
    'description' => null,
])

{{--
    Judul besar.

    Judul mulai besar di dalam konten, lalu menyusut ke toolbar saat halaman
    digulir. Dua gerakan berlawanan arah yang saling menyilang — itulah yang
    menjaga "di mana saya" tetap terjawab setelah judul aslinya tergulir
    pergi, tanpa breadcrumb yang mengulang apa yang sudah dikatakan sidebar.

    Elemen ini hanya menyediakan ref-nya; yang mengikat progresnya ke posisi
    gulir adalah largeTitle() di layout (lihat interactions/largeTitle.js).
--}}

<div {{ $attributes->class('flex flex-wrap items-end gap-3 pt-2 pb-1') }}>
    <div class="min-w-[200px] flex-1">
        @if ($heading)
            <h1 x-ref="large" class="origin-left text-4xl font-semibold text-ink">
                {{ $heading }}
            </h1>
        @endif

        @if ($description)
            <p class="measure mt-1.5 text-body text-ink-secondary">{{ $description }}</p>
        @endif

        {{ $slot }}
    </div>
</div>
