@props([
    'screen',
    'meta',
    'url' => null,
    // 'page' atau 'shell' — sama artinya dengan prop bernama sama di layout
    // base, supaya layar contoh berdiri di atas latar yang persis dipakai
    // aplikasi sungguhan. (Nama tag komponennya sengaja tidak ditulis di sini:
    // Blade ikut mengompilasi tag <x-…> yang muncul di dalam komentar.)
    'backdrop' => 'page',
])

{{--
    Bingkai layar contoh: bilah judul, penunjuk layar sebelumnya/berikutnya, dan
    "jendela peramban" palsu supaya jelas bahwa yang di dalam adalah satu
    halaman utuh, bukan potongan komponen.
--}}

@php
    $shell = $backdrop === 'shell';
    $screens = config('design-system.screens', []);
    $keys = array_keys($screens);
    $index = array_search($screen, $keys, true);
    $prev = $index > 0 ? $keys[$index - 1] : null;
    $next = $index !== false && $index < count($keys) - 1 ? $keys[$index + 1] : null;
@endphp

<x-layouts.base :title="$meta['title'].' — Layar contoh'">
    <header class="glass sticky top-0 z-40 rounded-none border-x-0 border-t-0">
        <div class="mx-auto flex h-[62px] max-w-[1320px] items-center gap-4 px-6">
            <a href="{{ route('design-system.patterns') }}#layar"
 class="flex shrink-0 items-center gap-2 text-base text-ink-secondary transition hover:text-ink">
                <x-ui.icon name="chevron-left" class="size-4" />
                Layar contoh
            </a>

            <div class="hidden items-baseline gap-3 sm:flex">
                <span class="font-mono text-2xs tracking-[0.12em] text-accent">
                    {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                </span>
                <h1 class="text-base font-semibold text-ink">{{ $meta['title'] }}</h1>
                <span class="text-base text-ink-muted">{{ $meta['summary'] }}</span>
            </div>

            <div class="flex-1"></div>

            <div class="flex items-center gap-1">
                @if ($prev)
                    <a href="{{ route('design-system.screen', $prev) }}" title="{{ $screens[$prev]['title'] }}"
 class="inline-flex size-9 items-center justify-center rounded-md text-ink-secondary transition hover:bg-fill-3 hover:text-ink">
                        <span class="sr-only">Layar sebelumnya</span>
                        <x-ui.icon name="chevron-left" class="size-4" />
                    </a>
                @endif

                @if ($next)
                    <a href="{{ route('design-system.screen', $next) }}" title="{{ $screens[$next]['title'] }}"
 class="inline-flex size-9 items-center justify-center rounded-md text-ink-secondary transition hover:bg-fill-3 hover:text-ink">
                        <span class="sr-only">Layar berikutnya</span>
                        <x-ui.icon name="chevron-right" class="size-4" />
                    </a>
                @endif
            </div>

            <button type="button" data-theme-toggle
 class="inline-flex size-9 items-center justify-center rounded-md text-ink-secondary transition hover:bg-fill-3 hover:text-ink focus-visible:outline-none focus-visible:shadow-[var(--focus-ring)]">
                <span class="sr-only">Ganti tema</span>
                <x-ui.icon name="sun-moon" class="size-5" />
            </button>
        </div>
    </header>

    <div class="mx-auto max-w-[1320px] px-6 py-9 pb-28">
        <div class="overflow-hidden rounded-xl border-[0.5px] border-line shadow-md {{ $shell ? 'bg-shell' : 'bg-surface' }}">
            <div class="flex h-9 items-center gap-2 border-b border-line bg-fill-4 px-3.5">
                <span class="size-[9px] rounded-full bg-line-strong"></span>
                <span class="size-[9px] rounded-full bg-line-strong"></span>
                <span class="size-[9px] rounded-full bg-line-strong"></span>
                <span class="ms-3 truncate font-mono text-xs text-ink-muted">{{ $url ?? 'app.contoh.id' }}</span>
            </div>

            <div class="relative">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-layouts.base>
