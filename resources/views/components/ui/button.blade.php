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
    // Satu aksi utama per layar: hanya `primary` yang memakai aksen. Dua tombol
    // aksen bersebelahan membuat pembaca tidak tahu mana yang dituju.
    $variants = [
        'primary' => 'border-transparent bg-[image:var(--mat-accent)] text-accent-on font-semibold shadow-[var(--bevel),var(--lift)] hover:brightness-95 active:shadow-press active:translate-y-px',
        'secondary' => 'border-line-strong bg-[image:var(--mat-raised)] text-ink shadow-[var(--bevel),var(--lift)] hover:brightness-95 active:shadow-press active:translate-y-px',
        'ghost' => 'border-transparent bg-transparent text-ink-secondary hover:bg-surface-inset hover:text-ink',
        'danger' => 'border-transparent bg-[image:var(--mat-danger)] text-ink-inverse font-semibold shadow-[var(--bevel),var(--lift)] active:shadow-press active:translate-y-px hover:opacity-[.88]',
        'success' => 'border-transparent bg-[image:var(--mat-success)] text-ink-inverse font-semibold shadow-[var(--bevel),var(--lift)] active:shadow-press active:translate-y-px hover:opacity-[.88]',
        'warning' => 'border-transparent bg-[image:var(--mat-warning)] text-ink-inverse font-semibold shadow-[var(--bevel),var(--lift)] active:shadow-press active:translate-y-px hover:opacity-[.88]',
        'dark' => 'border-transparent bg-ink text-surface-raised font-semibold hover:opacity-90',
    ];

    // Tinggi kontrol: 30 / 32 / 38 / 48. `icon` adalah tombol persegi seukuran
    // kontrol — dipakai kalau ikonnya berdiri sendiri. `xs` khusus aksi di
    // dalam baris tabel, di mana tinggi baris yang menentukan.
    $sizes = [
        'xs' => 'h-[30px] gap-1.5 rounded-sm px-2.5 text-sm2',
        'sm' => 'h-8 gap-1.5 rounded-sm px-3 text-[13px]',
        'md' => 'h-control gap-2 rounded-md px-[18px] text-sm',
        'lg' => 'h-12 gap-2 rounded-md px-[22px] text-[15px]',
        'icon' => 'size-control rounded-md p-0',
        'icon-round' => 'size-control rounded-full p-0',
    ];

    $classes = implode(' ', [
        'inline-flex shrink-0 items-center justify-center border font-medium whitespace-nowrap',
        'transition duration-120 ease-rizz outline-none',
        'focus-visible:ring-3 focus-visible:ring-accent-soft',
        'disabled:pointer-events-none disabled:border-line disabled:bg-surface-inset disabled:bg-none disabled:text-ink-muted disabled:shadow-none',
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['md'],
        $block ? 'w-full' : '',
    ]);
@endphp

@if ($href && ! $disabled)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        {{ $icon }}
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @disabled($disabled) {{ $attributes->class($classes) }}>
        {{ $icon }}
        {{ $slot }}
    </button>
@endif
