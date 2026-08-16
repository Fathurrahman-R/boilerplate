@props([
    'name',
    'label' => null,
    'value' => 0,
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'suffix' => '%',
    'hint' => null,
])

@php
    $id = $attributes->get('id', $name);
    $value = old($name, $value);
@endphp

{{--
    Track adalah lubang, tuas adalah benda yang menonjol — sama seperti input
    dan tombol. Input range aslinya tetap ada dan tetap bisa dijalankan dari
    papan ketik; yang digambar ulang hanya lapisan tampilannya.
--}}

<div x-data="{ value: {{ (int) $value }}, min: {{ (int) $min }}, max: {{ (int) $max }},
        get percent() { return ((this.value - this.min) / (this.max - this.min)) * 100 } }"
     {{ $attributes->except('id')->class('flex flex-col gap-3') }}>

    @if ($label)
        <div class="flex items-baseline justify-between gap-3">
            <x-ui.label :for="$id" class="mb-0">{{ $label }}</x-ui.label>
            <span class="num rounded-sm bg-surface-sunken px-2.5 py-0.5 text-[13px] text-ink shadow-well"
                  x-text="value + @js($suffix)"></span>
        </div>
    @endif

    <div class="relative h-4">
        <div class="absolute inset-0 rounded-full bg-surface-sunken p-[3px] shadow-well">
            <div class="h-2.5 rounded-full bg-[image:var(--mat-accent)] shadow-[inset_0_1px_0_rgb(255_255_255/0.4)]"
                 :style="`width: ${percent}%`"></div>
        </div>

        <span class="pointer-events-none absolute top-[-4px] -ms-3 size-6 rounded-full border border-line-strong bg-[image:var(--mat-raised)] shadow-lift"
              :style="`inset-inline-start: ${percent}%`"></span>

        <input type="range" id="{{ $id }}" name="{{ $name }}"
               min="{{ $min }}" max="{{ $max }}" step="{{ $step }}"
               x-model.number="value"
               class="absolute inset-0 w-full cursor-pointer opacity-0"
               @if ($label) aria-label="{{ $label }}" @endif>
    </div>

    <x-ui.field-note :id="$id.'-note'" :hint="$hint" :error="$errors->first($name)" />
</div>
