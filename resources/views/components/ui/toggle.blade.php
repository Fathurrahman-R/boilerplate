@props([
    'name',
    'label' => null,
    'checked' => false,
    'value' => '1',
    'hint' => null,
])

@php
    $id = $attributes->get('id', $name);
    $isChecked = (bool) old($name, $checked);
@endphp

{{--
    Tuas yang knob-nya bisa diseret.

    Knob dulunya ::after. Pseudo-element tidak bisa digerakkan dari
    JavaScript, jadi ia diganti elemen sungguhan — itu satu-satunya cara
    knob-nya bisa mengikuti jari alih-alih meloncat setelah klik.

    Warna track berubah menerus mengikuti posisi knob, bukan membalik sekali
    di akhir: umpan baliknya berjalan sepanjang gerakan. Dan yang menentukan
    hasil adalah ke mana gerakan itu menuju, bukan di mana jari kebetulan
    berhenti — sentakan cepat balik melewati titik awal tetap berakhir mundur.

    Input checkbox aslinya tetap ada dan tetap bisa dijalankan dengan Space.
--}}

<div x-data="dragToggle({ travel: 20, checked: {{ $isChecked ? 'true' : 'false' }} })"
     x-init="toggleInit()">
    <label for="{{ $id }}" class="inline-flex cursor-pointer items-center gap-2.5">
        {{-- Nilai "0" dikirim lebih dulu supaya field tetap terkirim saat
             togglenya mati; checkbox yang tidak dicentang tidak ikut
             terkirim. --}}
        <input type="hidden" name="{{ $name }}" value="0">

        <input type="checkbox"
               x-ref="input"
               id="{{ $id }}"
               name="{{ $name }}"
               value="{{ $value }}"
               class="peer sr-only"
               x-on:change="sync()"
               @checked($isChecked)
               {{ $attributes }}>

        <span x-ref="track"
              class="relative h-[31px] w-[51px] shrink-0 touch-none rounded-full transition-colors duration-[--dur-fast] peer-focus-visible:shadow-[var(--focus-ring)]"
              style="background: color-mix(in oklab, var(--accent) calc(var(--toggle-progress, 0) * 100%), var(--fill-1))">
            <span x-ref="knob"
                  class="absolute top-[2px] left-[2px] size-[27px] rounded-full bg-white shadow-md"></span>
        </span>

        @if ($label)
            <span class="text-base text-ink">{{ $label }}</span>
        @endif
    </label>

    @if ($hint)
        <p class="mt-1.5 text-sm text-ink-muted">{{ $hint }}</p>
    @endif
</div>
