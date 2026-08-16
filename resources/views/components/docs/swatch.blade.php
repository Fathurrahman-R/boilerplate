@props([
    'token',
    'use' => null,
    'class' => 'bg-surface',
])

{{-- Contoh warna: petaknya memakai kelas Tailwind yang sesungguhnya, jadi
     kalau tokennya salah, petaknya ikut salah — bukan cuma keterangannya. --}}

<div {{ $attributes->class('overflow-hidden rounded-md border border-line bg-surface-raised') }}>
    <div class="h-16 {{ $class }}"></div>

    <div class="border-t border-line px-3 py-2.5">
        <div class="font-mono text-[12.5px] text-ink">{{ $token }}</div>

        @if ($use)
            <div class="text-xs text-ink-muted">{{ $use }}</div>
        @endif
    </div>
</div>
