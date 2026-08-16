@props([
    'table' => null,
    'placeholder' => 'Cari…',
])

{{--
    Baris pencarian + filter di atas tabel.

    Filter dengan sedikit pilihan lebih baik jadi <x-ui.filter-chips>: pilihannya
    terbaca sekaligus dan langsung berlaku. Filter dengan pilihan banyak tetap
    <select name="..."> di dalam slot `filters`; formnya method GET, jadi
    nilainya otomatis jadi query string yang dibaca TableBuilder.

    Slot `bulk` diisi kalau tabelnya bisa dipilih — isinya hidup mengikuti
    `selected` dari komponen tabel.
--}}

<div class="flex flex-wrap items-center gap-2.5">
    <form method="GET" class="flex flex-wrap items-center gap-2.5">
        <div class="relative">
            <x-ui.icon name="search" class="pointer-events-none absolute inset-y-0 start-3 my-auto size-4 text-ink-muted" />

            <input type="search"
                   name="{{ $table?->searchParameter() ?? 'q' }}"
                   value="{{ $table?->search() }}"
                   placeholder="{{ $placeholder }}"
                   class="block h-[34px] w-[230px] max-w-full rounded-md border border-line bg-surface-sunken ps-9 pe-3 text-base2 text-ink shadow-well outline-none transition placeholder:text-ink-muted focus:border-accent focus:ring-3 focus:ring-accent-soft">
        </div>

        @isset($filters)
            {{ $filters }}

            {{-- Tombol Terapkan hanya berarti kalau ada kontrol yang menunggu
                 dikirim. Chip mengirim dirinya sendiri lewat tautan. --}}
            <x-ui.button type="submit" variant="secondary" size="sm" class="h-[34px]">
                <x-ui.icon name="sliders-horizontal" class="size-4" />
                Terapkan
            </x-ui.button>
        @endisset
    </form>

    @isset($chips)
        {{ $chips }}
    @endisset

    @if ($table?->hasActiveFilters())
        <a href="{{ $table->resetUrl() }}" class="text-base2 text-ink-muted underline-offset-2 transition hover:text-ink hover:underline">
            Reset
        </a>
    @endif

    @isset($bulk)
        <div class="ms-auto flex items-center gap-2.5">
            <span class="text-sm2 text-ink-muted" x-show="selected.length" x-cloak
                  x-text="selected.length + ' dipilih'"></span>

            <div class="flex items-center gap-2 transition-opacity duration-180"
                 :class="selected.length ? 'opacity-100' : 'pointer-events-none opacity-0'">
                {{ $bulk }}
            </div>
        </div>
    @endisset
</div>
