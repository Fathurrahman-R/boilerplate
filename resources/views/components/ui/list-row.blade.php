@props([
    'label' => null,
    'sublabel' => null,
    // Diisi berarti barisnya adalah tautan dan mendapat chevron di ujung.
    'href' => null,
    // Baris yang bisa diklik tanpa berpindah halaman (membuka panel, memicu
    // event). Chevron ikut muncul.
    'action' => false,
    'danger' => false,
])

{{--
    Satu baris di dalam grup daftar (x-ui.list).

    Susunannya selalu sama: penanda di depan, label dan keterangannya di
    tengah, nilai atau kontrolnya di belakang. Karena urutannya tetap, mata
    berhenti mencari — semua yang bisa diubah selalu ada di sisi yang sama.

    Pemisah antar-baris digambar sebagai ::before yang menjorok, bukan border
    yang menyentuh kedua tepi: garis penuh membelah kartunya jadi potongan
    terpisah, garis menjorok membacanya tetap satu benda berisi beberapa baris.

        x-ui.list-row label="Tema" sublabel="Ikut sistem"
          dengan slot `leading` berisi ikon dan slot `trailing` berisi badge
--}}

@php
    $interactive = $href !== null || $action;

    $classes = implode(' ', [
        'relative flex w-full items-center gap-3 px-4 py-3 text-start',
        'not-first:before:absolute not-first:before:inset-x-4 not-first:before:top-0',
        'not-first:before:h-px not-first:before:bg-line not-first:before:content-[\'\']',
        $interactive
            ? 'cursor-pointer transition-colors duration-[--dur-fast] hover:bg-fill-4 focus-visible:bg-fill-4 focus-visible:outline-none'
            : '',
        $danger ? 'text-danger' : 'text-ink',
    ]);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
@elseif ($action)
    <button type="button" {{ $attributes->class($classes) }}>
@else
    <div {{ $attributes->class($classes) }}>
@endif

@isset($leading)
    <span class="flex shrink-0 items-center text-ink-secondary">{{ $leading }}</span>
@endisset

<span class="min-w-0 flex-1">
    @if ($label)
        <span class="block truncate text-base font-medium">{{ $label }}</span>
    @endif

    @if ($sublabel)
        <span class="mt-0.5 block truncate text-sm text-ink-muted">{{ $sublabel }}</span>
    @endif

    {{ $slot }}
</span>

@isset($trailing)
    <span class="flex shrink-0 items-center gap-2 text-ink-secondary">{{ $trailing }}</span>
@endisset

@if ($interactive)
    <x-ui.icon name="chevron-right" class="size-4 shrink-0 text-ink-quaternary" />
@endif

@if ($href)
    </a>
@elseif ($action)
    </button>
@else
    </div>
@endif
