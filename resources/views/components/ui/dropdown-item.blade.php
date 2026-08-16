@props([
    'href' => null,
    'danger' => false,
    'shortcut' => null,
])

@php
    $classes = implode(' ', [
        'flex w-full items-center gap-2.5 rounded-sm px-2.5 py-2 text-left text-sm transition',
        '[&>svg]:size-4 [&>svg]:shrink-0 [&>svg]:text-ink-muted',
        $danger
            ? 'text-danger hover:bg-danger-soft [&>svg]:text-danger'
            : 'text-ink hover:bg-surface-inset',
    ]);
@endphp

<li role="none">
    @if ($href)
        <a href="{{ $href }}" role="menuitem" {{ $attributes->class($classes) }}>
            {{ $slot }}
            @if ($shortcut)
                <span class="ms-auto font-mono text-[11px] text-ink-muted">{{ $shortcut }}</span>
            @endif
        </a>
    @else
        <button type="button" role="menuitem" {{ $attributes->class($classes) }}>
            {{ $slot }}
            @if ($shortcut)
                <span class="ms-auto font-mono text-[11px] text-ink-muted">{{ $shortcut }}</span>
            @endif
        </button>
    @endif
</li>
