@props([
    'name',
    'label' => null,
    'value' => null,
    'checked' => false,
    'hint' => null,
])

@php
    $id = $attributes->get('id', $name.'_'.$value);
@endphp

<div class="flex items-start gap-2.5">
    <div class="flex h-5 items-center">
        <input type="radio"
               id="{{ $id }}"
               name="{{ $name }}"
               value="{{ $value }}"
               @checked($checked)
               {{-- Lingkarannya tetap cekung saat dipilih; titiknya digambar
                    sebagai background, bukan dengan menebalkan border, supaya
                    bayangan `well` tidak hilang. --}}
               {{ $attributes->class([
                   'size-[18px] shrink-0 cursor-pointer appearance-none rounded-full border border-line-strong bg-surface-sunken shadow-well transition',
                   'checked:border-accent checked:bg-[radial-gradient(circle,var(--accent)_0_4px,transparent_4.5px)]',
                   'focus-visible:outline-none focus-visible:ring-3 focus-visible:ring-accent-soft',
                   'disabled:cursor-not-allowed disabled:opacity-60',
               ]) }}>
    </div>

    @if ($label || $hint)
        <div>
            <label for="{{ $id }}" class="cursor-pointer text-sm text-ink">{{ $label }}</label>

            @if ($hint)
                <p class="text-[12.5px] text-ink-muted">{{ $hint }}</p>
            @endif
        </div>
    @endif
</div>
