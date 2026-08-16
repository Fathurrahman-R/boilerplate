@props([
    'items' => [],
    'root' => 'Dashboard',
])

{{-- $items berbentuk ['Label' => url, 'Label terakhir' => null] --}}

<nav aria-label="Breadcrumb" {{ $attributes }}>
    <ol class="flex flex-wrap items-center gap-1 text-[13px] text-ink-muted">
        <li>
            <a href="{{ route('dashboard') }}" class="truncate transition hover:text-ink">{{ $root }}</a>
        </li>

        @foreach ($items as $label => $url)
            <li class="flex items-center gap-1">
                <x-ui.icon name="chevron-right" class="size-3.5 text-ink-muted" />

                @if ($url && ! $loop->last)
                    <a href="{{ $url }}" class="transition hover:text-ink">{{ $label }}</a>
                @else
                    <span class="font-medium text-ink" aria-current="page">{{ $label }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
