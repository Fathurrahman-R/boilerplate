@props([
    'name',
    'label' => null,
    'value' => null,
    'hint' => null,
    'min' => null,
    'max' => null,
])

{{--
    Pemilih tanggal bawaan browser. Tidak ada pustaka kalender tambahan: yang
    bawaan sudah bisa diketik, mengerti keyboard, dan mengikuti setelan tanggal
    sistem pengguna.

    Nilainya selalu berformat Y-m-d, jadi bisa langsung masuk ke kolom `date`.
--}}

@php
    $errorKey = str_replace(['[', ']'], ['.', ''], $name);
    $invalid = $errors->has($errorKey);
    $id = $attributes->get('id', $name);
@endphp

<div>
    @if ($label)
        <x-ui.label :for="$id" :invalid="$invalid">{{ $label }}</x-ui.label>
    @endif

    <input type="date"
           id="{{ $id }}"
           name="{{ $name }}"
           value="{{ old($errorKey, $value) }}"
           @if ($min) min="{{ $min }}" @endif
           @if ($max) max="{{ $max }}" @endif
           @if ($invalid) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
           {{ $attributes->class([
               'block h-control w-full rounded-md border bg-surface-sunken px-3 text-sm text-ink shadow-well',
               'outline-none transition focus:border-accent focus:ring-3 focus:ring-accent-soft',
               '[&::-webkit-calendar-picker-indicator]:cursor-pointer [&::-webkit-calendar-picker-indicator]:opacity-60',
               'border-line' => ! $invalid,
               'border-danger' => $invalid,
           ]) }}>

    <x-ui.field-note :id="$id.'-error'" :error="$invalid ? $errors->first($errorKey) : null" :hint="$hint" />
</div>
