@props([
    'headers' => [],
    'table' => null,
    'empty' => 'Belum ada data',
    // Daftar id baris di halaman ini. Diisi berarti tabel bisa dipilih:
    // kolom centang muncul di depan dan slot `bulk` di toolbar ikut hidup.
    'selectable' => [],
    // Diisi berarti tiap baris punya kolom chevron di ujung dan bisa diklik
    // untuk membuka panel detail.
    'openable' => false,
])

{{--
    Kerangka tabel.

    $headers berbentuk ['kolom_db' => 'Label'] atau ['Label'] untuk kolom yang
    tidak bisa diurutkan. Kalau $table (TableBuilder) diberikan, header kolom
    yang terdaftar sebagai sortable otomatis jadi tautan pengurutan.

    Tabel selalu duduk di permukaan solid — tidak pernah di atas kaca, dan
    barisnya tidak pernah punya kedalaman. Keduanya membuat angka lebih sulit
    dibaca, dan angka yang menang.

    Pencarian, filter, dan pagination tinggal di dalam kartu yang sama lewat
    slot `toolbar` dan `footer` — semuanya satu benda, bukan tiga potong yang
    kebetulan bertumpuk.

    Hanya bagian tabelnya yang menggulir mendatar, supaya toolbar dan pagination
    tetap di tempatnya saat kolomnya banyak.
--}}

@php($isSelectable = $selectable !== [])

<div class="overflow-hidden rounded-lg border border-line bg-surface-raised shadow-lift"
     @if ($isSelectable) x-data="tableSelection(@js(array_values($selectable)))" @endif>
    @isset($toolbar)
        <div class="border-b border-line px-4 py-[13px]">{{ $toolbar }}</div>
    @endisset

    <div class="overflow-x-auto">
    <table {{ $attributes->class('w-full text-left text-base2 text-ink-secondary') }}>
        <thead class="bg-surface-sunken">
            <tr>
                @if ($isSelectable)
                    <th scope="col" class="w-11 ps-4 pe-0 py-2.5">
                        <input type="checkbox" class="form-check"
                               aria-label="Pilih semua baris"
                               :checked="allChecked" x-on:change="toggleAll()">
                    </th>
                @endif

                @isset($head)
                    {{ $head }}
                @else
                    @foreach ($headers as $column => $label)
                        @php($sortable = $table && is_string($column) && $table->isSortable($column))

                        <th scope="col" class="px-4 py-2.5 text-xs2 font-semibold tracking-[0.05em] whitespace-nowrap text-ink-muted uppercase">
                            @if ($sortable)
                                <a href="{{ $table->sortUrl($column) }}" class="inline-flex items-center gap-1.5 transition hover:text-ink">
                                    {{ $label }}

                                    @if ($table->sortColumn() === $column)
                                        <x-ui.icon name="chevron-up"
                                                   class="size-3.5 {{ $table->sortDirection() === 'desc' ? 'rotate-180' : '' }}" />
                                    @else
                                        <x-ui.icon name="chevrons-up-down" class="size-3.5 opacity-40" />
                                    @endif
                                </a>
                            @else
                                {{ $label }}
                            @endif
                        </th>
                    @endforeach
                @endisset

                @if ($openable)
                    <th scope="col" class="w-11"><span class="sr-only">Detail</span></th>
                @endif
            </tr>
        </thead>

        <tbody>
            {{ $slot }}
        </tbody>
    </table>
    </div>

    @isset($emptyState)
        {{ $emptyState }}
    @endisset

    @isset($footer)
        <div class="border-t border-line px-4 py-[11px] text-[13px] text-ink-muted">{{ $footer }}</div>
    @endisset
</div>
