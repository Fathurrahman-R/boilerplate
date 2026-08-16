@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'hint' => null,
    'required' => false,
    'prefix' => null,
])

@php
    // Nama field boleh berbentuk array (mis. "items[0][qty]"); $errors memakai
    // notasi titik, jadi diterjemahkan lebih dulu.
    $errorKey = str_replace(['[', ']'], ['.', ''], $name);
    $invalid = $errors->has($errorKey);
    $id = $attributes->get('id', $name);
@endphp

<div>
    @if ($label)
        <x-ui.label :for="$id" :required="$required" :invalid="$invalid">{{ $label }}</x-ui.label>
    @endif

    <div class="relative">
        @if ($prefix)
            <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3 text-ink-muted">
                {{ $prefix }}
            </div>
        @endif

        <input type="{{ $type }}"
               id="{{ $id }}"
               name="{{ $name }}"
               value="{{ old($errorKey, $value) }}"
               @required($required)
               @if ($invalid) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
               {{ $attributes->class([
                   'block h-control w-full rounded-md border bg-surface-sunken px-3 text-sm text-ink shadow-well',
                   'outline-none transition placeholder:text-ink-muted',
                   'focus:border-accent focus:ring-3 focus:ring-accent-soft',
                   'disabled:cursor-not-allowed disabled:opacity-60',
                   'ps-10' => (bool) $prefix,
                   'border-line' => ! $invalid,
                   'border-danger' => $invalid,
               ]) }}>
    </div>

    <x-ui.field-note :id="$id.'-error'" :error="$invalid ? $errors->first($errorKey) : null" :hint="$hint" />
</div>
