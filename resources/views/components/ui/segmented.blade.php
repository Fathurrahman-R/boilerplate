@props([
    'name' => null,
    'options' => [],
    'selected' => null,
])

{{--
    Pilihan tunggal yang jumlahnya sedikit dan setara — periode, mode tampilan,
    saringan cepat. Track-nya cekung, pilihan aktif menonjol.

    $options berbentuk ['nilai' => 'Label'].

    Tanpa `name` komponen ini murni tampilan (state di Alpine). Dengan `name`,
    nilai terpilih ikut terkirim sebagai input tersembunyi.
--}}

@php
    $selected ??= array_key_first($options);
@endphp

<div x-data="{ picked: @js((string) $selected) }"
     {{ $attributes->class('inline-flex gap-0.5 rounded-md bg-surface-sunken p-[3px] shadow-well') }}
     role="radiogroup">

    @if ($name)
        <input type="hidden" name="{{ $name }}" :value="picked">
    @endif

    @foreach ($options as $value => $label)
        <button type="button" role="radio"
                :aria-checked="picked === @js((string) $value)"
                x-on:click="picked = @js((string) $value); $dispatch('segmented-change', @js((string) $value))"
                class="rounded-sm px-4 py-[7px] text-[13.5px] transition duration-160 outline-none focus-visible:ring-3 focus-visible:ring-accent-soft"
                :class="picked === @js((string) $value)
                    ? 'bg-[image:var(--mat-raised)] font-semibold text-ink shadow-[var(--bevel),var(--lift)]'
                    : 'text-ink-secondary hover:text-ink'">
            {{ $label }}
        </button>
    @endforeach
</div>
