@props([
    'for' => null,
    'required' => false,
    'invalid' => false,
])

{{-- Label selalu di atas field, tidak pernah digantikan placeholder. --}}

<label @if ($for) for="{{ $for }}" @endif
       {{ $attributes->class([
           'mb-1.5 block text-[13px] font-semibold',
           'text-ink' => ! $invalid,
           'text-danger' => $invalid,
       ]) }}>
    {{ $slot }}

    @if ($required)
        <span class="text-danger" aria-hidden="true">*</span>
    @endif
</label>
