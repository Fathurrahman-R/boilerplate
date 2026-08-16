@props([
    // [['label' => 'Lunas', 'tone' => 'chart-1', 'value' => 'Rp 210jt'], …]
    'items' => [],
    // 'row' — sejajar di bawah grafik; 'stack' — daftar bernilai di samping.
    'layout' => 'row',
])

<div {{ $attributes->class([
    'text-xs text-ink-muted',
    'flex flex-wrap items-center gap-3.5' => $layout === 'row',
    'flex flex-col gap-2.5' => $layout === 'stack',
]) }}>
    @foreach ($items as $item)
        <div class="flex items-center gap-2">
            <span class="size-[9px] shrink-0 rounded-[3px] bg-{{ $item['tone'] ?? 'chart-1' }}"></span>
            <span class="{{ $layout === 'stack' ? 'flex-1' : '' }} text-ink-secondary">{{ $item['label'] }}</span>

            @if ($item['value'] ?? null)
                <span class="num text-sm2 text-ink">{{ $item['value'] }}</span>
            @endif
        </div>
    @endforeach
</div>
