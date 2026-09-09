@props([
    'href' => null,
    'danger' => false,
    'shortcut' => null,
])

@php
    $classes = implode(' ', [
        'flex w-full items-center gap-2.5 rounded-sm px-2.5 py-2 text-left text-base',
        'transition-colors duration-[--dur-fast] outline-none focus-visible:bg-fill-3',
        '[&>svg]:size-4 [&>svg]:shrink-0 [&>svg]:text-ink-muted',
        $danger
            ? 'text-danger hover:bg-danger-soft [&>svg]:text-danger'
            : 'text-ink hover:bg-fill-3',
    ]);
@endphp

<li role="none">
    @if ($href)
        <a href="{{ $href }}" role="menuitem" tabindex="-1" {{ $attributes->class($classes) }}>
            {{ $slot }}
            @if ($shortcut)
                <span class="ms-auto text-xs text-ink-quaternary">{{ $shortcut }}</span>
            @endif
        </a>
    @else
        <button type="button" role="menuitem" tabindex="-1" {{ $attributes->class($classes) }}>
            {{ $slot }}
            @if ($shortcut)
                <span class="ms-auto text-xs text-ink-quaternary">{{ $shortcut }}</span>
            @endif
        </button>
    @endif
</li>
