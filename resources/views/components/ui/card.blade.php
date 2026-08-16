@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
    'glass' => false,
    'material' => false,
])

{{--
    Card biasa duduk di permukaan solid. Varian `glass` hanya untuk card yang
    berdiri di atas latar bertekstur — kartu metrik, hero, panel ringkasan.
    Jangan dipakai membungkus tabel, form, atau teks panjang.

    Varian `material` adalah bidang yang menampung kontrol — kelompok tombol,
    tuas, slider. Bukan untuk teks panjang atau tabel.
--}}

<div {{ $attributes->class([
    'rounded-lg',
    'glass' => $glass,
    'mat-panel' => $material && ! $glass,
    'border border-line bg-surface-raised shadow-lift' => ! $glass && ! $material,
]) }}>
    @if ($title || $subtitle || isset($header))
        <div class="flex items-start justify-between gap-4 border-b border-line px-5 py-4">
            <div>
                @if ($title)
                    <h2 class="font-display text-base font-semibold text-ink">{{ $title }}</h2>
                @endif

                @if ($subtitle)
                    <p class="mt-1 text-sm text-ink-secondary">{{ $subtitle }}</p>
                @endif

                {{ $header ?? '' }}
            </div>

            @isset($actions)
                <div class="flex shrink-0 items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    <div @class(['p-5' => $padding])>
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="border-t border-line px-5 py-4">{{ $footer }}</div>
    @endisset
</div>
