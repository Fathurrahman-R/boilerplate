@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'submit',
    'icon' => null,
    'block' => false,
    'disabled' => false,
])

@php
    // Satu aksi utama per layar: hanya `primary` yang memakai aksen. Dua
    // tombol aksen bersebelahan membuat pembaca tidak tahu mana yang dituju.
    //
    // Isiannya rata. Gradien dan bevel dulu dipakai untuk memberi tahu bahwa
    // sesuatu bisa ditekan; sekarang yang memberi tahu adalah tombolnya
    // benar-benar bereaksi saat jari turun.
    $variants = [
        'primary' => 'bg-accent text-accent-on font-semibold hover:bg-accent-hover',
        'secondary' => 'bg-fill-3 text-ink hover:bg-fill-2',
        'ghost' => 'bg-transparent text-ink-secondary hover:bg-fill-4 hover:text-ink',
        'danger' => 'bg-danger text-white font-semibold hover:brightness-110',
        'success' => 'bg-success text-white font-semibold hover:brightness-110',
        'warning' => 'bg-warning text-white font-semibold hover:brightness-110',
        'dark' => 'bg-ink text-surface-raised font-semibold hover:opacity-90',
    ];

    // Tinggi kontrol: 28 / 32 / 36 / 44. Ukuran `lg` sengaja 44px — itu batas
    // bawah sasaran sentuh yang masih nyaman dikenai jari.
    $sizes = [
        'xs' => 'h-7 gap-1.5 rounded-sm px-2.5 text-sm',
        'sm' => 'h-8 gap-1.5 rounded-sm px-3 text-base',
        'md' => 'h-control gap-2 rounded-md px-4 text-base',
        'lg' => 'h-11 gap-2 rounded-lg px-5 text-body',
        'icon' => 'size-control rounded-md p-0',
        'icon-round' => 'size-control rounded-full p-0',
    ];

    $classes = implode(' ', [
        'inline-flex shrink-0 items-center justify-center border-0 font-medium whitespace-nowrap',
        'transition-colors duration-[--dur-fast] outline-none',
        'focus-visible:shadow-[var(--focus-ring)]',
        'disabled:pointer-events-none disabled:bg-fill-4 disabled:text-ink-quaternary',
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['md'],
        $block ? 'w-full' : '',
    ]);
@endphp

{{--
    Umpan balik tekan dipasang lewat `pressable()`: skalanya menyusut sedikit
    saat jari *turun*, bukan saat dilepas. Karena spring-nya selalu berangkat
    dari nilai yang sedang tampil, menyeret jari keluar lalu kembali masuk
    tidak pernah menghasilkan lompatan — targetnya berubah, gerakannya
    menyambung.
--}}

@if ($href && ! $disabled)
    <a href="{{ $href }}"
       x-data="pressable()" x-bind="pressBind"
       {{ $attributes->class($classes) }}>
        {{ $icon }}
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @disabled($disabled)
            x-data="pressable()" x-bind="pressBind"
            {{ $attributes->class($classes) }}>
        {{ $icon }}
        {{ $slot }}
    </button>
@endif
