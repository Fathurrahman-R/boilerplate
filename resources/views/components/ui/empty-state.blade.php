@props([
    'title' => 'Belum ada data',
    'description' => null,
    'icon' => 'inbox',
])

{{-- Layar kosong adalah ajakan bertindak, bukan permintaan maaf. --}}

<div {{ $attributes->class('flex flex-col items-center justify-center gap-1.5 px-6 py-12 text-center') }}>
    <div class="mb-1.5 flex size-11 items-center justify-center rounded-lg bg-surface-sunken text-ink-muted shadow-well">
        <x-ui.icon :name="$icon" class="size-[21px]" />
    </div>

    <p class="font-display text-lg2 font-semibold text-ink">{{ $title }}</p>

    @if ($description)
        <p class="max-w-[36ch] text-base2 text-ink-secondary">{{ $description }}</p>
    @endif

    @if ($slot->isNotEmpty())
        <div class="mt-3">{{ $slot }}</div>
    @endif
</div>
