@props([
    'name',
    'label' => null,
    'value' => '1',
    'checked' => false,
    'hint' => null,
])

@php
    $errorKey = str_replace(['[', ']'], ['.', ''], $name);
    $id = $attributes->get('id', $name.'_'.$value);
@endphp

<div class="flex items-start gap-2.5">
    <div class="flex h-5 items-center">
        {{--
            Kotaknya cekung saat kosong (well) dan terisi aksen saat dicentang —
            aturan material yang sama dengan input: yang cekung bisa diisi.
        --}}
        <input type="checkbox"
               id="{{ $id }}"
               name="{{ $name }}"
               value="{{ $value }}"
               @checked($checked)
               {{ $attributes->class('form-check') }}>
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

@error($errorKey)
    <p class="mt-1.5 text-[12.5px] text-danger">{{ $message }}</p>
@enderror
