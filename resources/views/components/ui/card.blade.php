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

<div @if ($glass) data-mat="thin" @endif
     {{ $attributes->class([
         'rounded-xl',
         'material' => $glass,
         'border-[0.5px] border-line bg-surface-raised shadow-sm' => ! $glass,
     ]) }}>
    @if ($title || $subtitle || isset($header))
        <div class="relative flex items-start justify-between gap-4 px-5 py-4
                    after:absolute after:inset-x-5 after:bottom-0 after:h-px after:bg-line after:content-['']">
            <div>
                @if ($title)
                    <h2 class="text-lg font-semibold text-ink">{{ $title }}</h2>
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
        <div class="relative px-5 py-4
                    before:absolute before:inset-x-5 before:top-0 before:h-px before:bg-line before:content-['']">{{ $footer }}</div>
    @endisset
</div>
