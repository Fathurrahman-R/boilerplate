@props([
    // [['text' => 'Faktur INV-2048 dibayar', 'time' => '2 jam lalu'], …]
    // Item pertama dianggap yang terbaru.
    'items' => [],
])

<ol {{ $attributes->class('flex flex-col gap-4') }}>
    @foreach ($items as $item)
        <li class="flex gap-3">
            <span class="mt-[7px] size-2 shrink-0 rounded-full {{ $loop->first ? 'bg-accent' : 'bg-line-strong' }}"></span>

            <div class="min-w-0 flex-1">
                <p class="text-base2 text-ink">{{ $item['text'] }}</p>

                @if ($item['time'] ?? null)
                    <p class="mt-0.5 text-xs text-ink-muted">{{ $item['time'] }}</p>
                @endif
            </div>
        </li>
    @endforeach
</ol>
