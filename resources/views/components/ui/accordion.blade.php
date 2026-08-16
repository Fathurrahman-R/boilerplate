@props([
    // Hanya satu panel terbuka pada satu waktu.
    'single' => true,
])

<div x-data="{ open: null, single: @js($single) }" {{ $attributes->class('flex flex-col') }}>
    {{ $slot }}
</div>
