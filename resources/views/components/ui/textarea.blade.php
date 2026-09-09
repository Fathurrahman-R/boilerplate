@props([
    'name',
    'label' => null,
    'value' => null,
    'hint' => null,
    'rows' => 4,
    'required' => false,
])

@php
    $errorKey = str_replace(['[', ']'], ['.', ''], $name);
    $invalid = $errors->has($errorKey);
    $id = $attributes->get('id', $name);
@endphp

<div>
    @if ($label)
        <x-ui.label :for="$id" :required="$required" :invalid="$invalid">{{ $label }}</x-ui.label>
    @endif

    <textarea id="{{ $id }}"
              name="{{ $name }}"
              rows="{{ $rows }}"
              @required($required)
              @if ($invalid) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
              {{ $attributes->class([
                  'block w-full resize-y rounded-md border-[0.5px] bg-fill-4 px-3 py-2.5 text-base leading-relaxed text-ink',
                  'outline-none transition-colors duration-[--dur-fast] placeholder:text-ink-quaternary',
                  'focus:bg-surface-raised focus:shadow-[var(--focus-ring)]',
                  'border-line' => ! $invalid,
                  'border-danger' => $invalid,
              ]) }}>{{ old($errorKey, $value) }}</textarea>

    <x-ui.field-note :id="$id.'-error'" :error="$invalid ? $errors->first($errorKey) : null" :hint="$hint" />
</div>
