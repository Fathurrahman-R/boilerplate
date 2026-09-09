@props([
    'title' => null,
    'heading' => null,
    'description' => null,
])

{{--
    Layar masuk dan daftar.

    Dulu layout ini memasang panel kutipan pelanggan dan tiga metrik di
    sebelah formulir. Itu pola halaman penjualan, bukan pola layar masuk:
    orang yang sudah sampai di sini tidak sedang dibujuk, ia sedang mencoba
    masuk — dan testimoni di sebelah kolom kata sandi hanya menambah sesuatu
    yang harus diabaikan lebih dulu.

    Yang tersisa satu kolom sempit di tengah. Slot `aside` tetap diterima
    supaya pemanggil lama tidak pecah, tapi isinya turun ke bawah formulir
    sebagai catatan tenang, bukan berdiri sejajar merebut perhatian.
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

            @isset($aside)
                <div class="mt-10 border-t-[0.5px] border-line pt-6">
                    {{ $aside }}
                </div>
            @endisset
        </div>

        <button type="button" data-theme-toggle
                x-data="pressable()" x-bind="pressBind"
                class="fixed end-4 top-4 inline-flex size-9 items-center justify-center rounded-md bg-fill-3 text-ink-secondary focus-visible:outline-none">
            <span class="sr-only">Ganti tema</span>
            <x-ui.icon name="sun-moon" class="size-4" />
        </button>
    </div>
</x-layouts.base>
