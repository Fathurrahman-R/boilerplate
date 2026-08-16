@props([
    'name',
    'label' => null,
    'checked' => false,
    'value' => '1',
    'hint' => null,
])

@php
    $id = $attributes->get('id', $name);
@endphp

<div>
    <label for="{{ $id }}" class="inline-flex cursor-pointer items-center gap-2.5">
        {{-- Nilai "0" dikirim lebih dulu supaya field tetap terkirim saat
             togglenya mati; checkbox yang tidak dicentang tidak ikut terkirim. --}}
        <input type="hidden" name="{{ $name }}" value="0">

        <input type="checkbox"
               id="{{ $id }}"
               name="{{ $name }}"
               value="{{ $value }}"
               class="peer sr-only"
               @checked($checked)
               {{ $attributes }}>

        {{-- Track cekung, tuas menonjol — persis kebalikan satu sama lain.
             Tuasnya dibuat sebagai ::after supaya tetap jadi saudara kandung
             input, syarat agar varian peer-checked mengenainya. --}}
        <span class="relative h-[22px] w-[38px] shrink-0 rounded-full bg-surface-sunken shadow-well transition-colors duration-200
                     after:absolute after:top-[2px] after:left-[2px] after:size-[18px] after:rounded-full after:border after:border-line-strong
                     after:bg-[image:var(--mat-raised)] after:shadow-[var(--bevel),var(--lift)] after:transition-transform after:duration-200 after:ease-rizz after:content-['']
                     peer-checked:bg-[image:var(--mat-accent)] peer-checked:after:translate-x-4
                     peer-focus-visible:ring-3 peer-focus-visible:ring-accent-soft"></span>

        @if ($label)
            <span class="text-sm text-ink">{{ $label }}</span>
        @endif
    </label>

    @if ($hint)
        <p class="mt-1.5 text-[12.5px] text-ink-muted">{{ $hint }}</p>
    @endif
</div>
