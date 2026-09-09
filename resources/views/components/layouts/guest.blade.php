@props([
    'title' => null,
    'heading' => null,
    'description' => null,
])

{{--
    Layar autentikasi: satu kolom sempit, terpusat, tanpa gangguan.

    Tidak ada panel testimoni, tidak ada metrik, tidak ada apa pun yang
    meminta perhatian. Orang yang sedang memasukkan kata sandi punya satu
    tujuan, dan segala sesuatu yang lain di layar hanya memperlambatnya.
--}}

<x-layouts.base :title="$title ?? $heading">
    <div class="bg-glow relative flex min-h-screen flex-col items-center justify-center px-5"
         style="padding-block: calc(2.5rem + var(--safe-t)) calc(2.5rem + var(--safe-b))">

        <div class="w-full max-w-[400px]">
            <a href="{{ url('/') }}" class="mb-8 flex items-center justify-center gap-2.5">
                <span class="flex size-8 items-center justify-center rounded-md bg-accent text-base font-bold text-accent-on">
                    {{ mb_substr(config('app.name'), 0, 1) }}
                </span>
                <span class="text-lg font-semibold text-ink">{{ config('app.name') }}</span>
            </a>

            @if ($heading)
                <h1 class="text-center text-2xl font-semibold text-ink">{{ $heading }}</h1>
            @endif

            @if ($description)
                <p class="mx-auto mt-2 max-w-[34ch] text-center text-body text-ink-secondary">
                    {{ $description }}
                </p>
            @endif

            <div class="mt-7 flex flex-col gap-4">
                {{ $slot }}
            </div>
        </div>

        <button type="button" data-theme-toggle
                x-data="pressable()" x-bind="pressBind"
                class="mt-8 inline-flex size-9 items-center justify-center rounded-md bg-fill-3 text-ink-secondary focus-visible:outline-none">
            <span class="sr-only">Ganti tema</span>
            <x-ui.icon name="sun-moon" class="size-4" />
        </button>
    </div>
</x-layouts.base>
