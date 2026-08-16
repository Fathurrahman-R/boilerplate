@props([
    // Id baris. Diisi kalau tabelnya `selectable` — kolom centang digambar di
    // sini supaya urutan selnya tidak perlu diatur ulang di tiap halaman.
    'id' => null,
    // Tabel induknya selectable, tapi baris ini terkunci — kolom centang
    // tetap harus digambar (kosong) supaya jumlah kolom tidak bergeser
    // dibanding baris lain yang punya checkbox.
    'selectable' => null,
    // URL fragmen panel detail. Diisi berarti seluruh baris bisa diklik.
    'panel' => null,
])

@php($showCheckboxColumn = $id !== null || $selectable)

{{--
    Klik di dalam elemen ber-`data-row-action` (tombol, tautan, centang) tidak
    ikut membuka panel — kalau tidak, menekan tombol Hapus akan selalu membuka
    detailnya lebih dulu.
--}}

<tr @if ($panel)
        x-on:click="$event.target.closest('[data-row-action]') || $dispatch('drawer-remote-open', @js($panel))"
    @endif
    @if ($id !== null)
        :class="has(@js($id)) && 'bg-accent-soft'"
    @endif
    {{ $attributes->class([
        'border-t border-line transition-colors duration-140 first:border-t-0 hover:bg-surface-inset',
        'cursor-pointer' => (bool) $panel,
    ]) }}>

    @if ($showCheckboxColumn)
        <td class="w-11 py-3 ps-4 pe-0 align-middle" data-row-action>
            @if ($id !== null)
                <input type="checkbox" class="form-check"
                       aria-label="Pilih baris"
                       :checked="has(@js($id))" x-on:change="toggle(@js($id))">
            @endif
        </td>
    @endif

    {{ $slot }}

    @if ($panel)
        <td class="w-11 py-3 pe-4 ps-0 text-end align-middle">
            <x-ui.icon name="chevron-right" class="inline size-4 text-ink-muted" />
        </td>
    @endif
</tr>
