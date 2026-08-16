@props([
    'name',
    'label' => null,
    'value' => 1,
    'min' => 0,
    'max' => null,
    'step' => 1,
    'hint' => null,
])

{{-- Untuk jumlah kecil yang lebih sering disesuaikan satu-satu daripada
     diketik ulang: lisensi, kursi, kuantitas. --}}

@php
    $errorKey = str_replace(['[', ']'], ['.', ''], $name);
    $invalid = $errors->has($errorKey);
    $id = $attributes->get('id', $name);
    $current = (int) old($errorKey, $value);
@endphp

<div x-data="{
        qty: {{ $current }},
        min: {{ $min }},
        max: {{ $max === null ? 'null' : (int) $max }},
        step: {{ $step }},
        bump(by) {
            const next = this.qty + by;
            if (next < this.min) return;
            if (this.max !== null && next > this.max) return;
            this.qty = next;
        },
     }">
    @if ($label)
        <x-ui.label :for="$id" :invalid="$invalid">{{ $label }}</x-ui.label>
    @endif

    <div class="inline-flex w-fit items-center gap-0.5 rounded-md bg-surface-sunken p-[3px] shadow-well">
        <button type="button" x-on:click="bump(-step)" :disabled="qty <= min"
                class="inline-flex size-[34px] items-center justify-center rounded-sm border border-line bg-[image:var(--mat-raised)] text-ink shadow-[var(--bevel),var(--lift)] transition active:shadow-press active:translate-y-px disabled:pointer-events-none disabled:opacity-40 focus-visible:outline-none focus-visible:ring-3 focus-visible:ring-accent-soft">
            <span class="sr-only">Kurangi</span>
            <x-ui.icon name="minus" class="size-4" />
        </button>

        <input type="number" id="{{ $id }}" name="{{ $name }}" x-model.number="qty"
               min="{{ $min }}" @if ($max !== null) max="{{ $max }}" @endif step="{{ $step }}"
               class="w-14 border-0 bg-transparent text-center num text-[15px] text-ink outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none"
               {{ $attributes }}>

        <button type="button" x-on:click="bump(step)" :disabled="max !== null && qty >= max"
                class="inline-flex size-[34px] items-center justify-center rounded-sm border border-line bg-[image:var(--mat-raised)] text-ink shadow-[var(--bevel),var(--lift)] transition active:shadow-press active:translate-y-px disabled:pointer-events-none disabled:opacity-40 focus-visible:outline-none focus-visible:ring-3 focus-visible:ring-accent-soft">
            <span class="sr-only">Tambah</span>
            <x-ui.icon name="plus" class="size-4" />
        </button>
    </div>

    <x-ui.field-note :error="$invalid ? $errors->first($errorKey) : null" :hint="$hint" />
</div>
