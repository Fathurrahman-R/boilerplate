@props([
    // ['Data akun', 'Hak akses', 'Konfirmasi']
    'steps' => [],
    // Indeks langkah yang sedang berjalan, mulai dari 1.
    'current' => 1,
])

<ol {{ $attributes->class('flex items-center gap-2') }}>
    @foreach ($steps as $index => $step)
        @php
            $number = $index + 1;
            $done = $number < $current;
            $active = $number === $current;
        @endphp

        <li class="flex items-center gap-2 {{ $loop->last ? '' : 'flex-1' }}">
            <span @class([
                'flex size-6 shrink-0 items-center justify-center rounded-full text-[12px] font-semibold',
                'bg-[image:var(--mat-accent)] text-accent-on shadow-lift' => $done || $active,
                'bg-surface-sunken text-ink-muted shadow-well' => ! $done && ! $active,
            ])>
                @if ($done)
                    <x-ui.icon name="check" class="size-3.5" />
                @else
                    {{ $number }}
                @endif
            </span>

            <span @class([
                'text-base2 whitespace-nowrap',
                'font-semibold text-ink' => $active,
                'text-ink-secondary' => $done,
                'text-ink-muted' => ! $done && ! $active,
            ])>{{ $step }}</span>

            @unless ($loop->last)
                <span class="h-0.5 flex-1 rounded-full {{ $done ? 'bg-accent' : 'bg-line-strong' }}"></span>
            @endunless
        </li>
    @endforeach
</ol>
