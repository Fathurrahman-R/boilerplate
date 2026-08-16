@props([
    'title' => null,
    'code' => null,
    'sunken' => false,
])

{{--
    Pratinjau komponen sungguhan di atas, potongan kode pemakaiannya di bawah.
    Karena yang dirender adalah komponen yang sama dengan yang dipakai
    aplikasi, halaman ini ikut berubah begitu komponennya berubah.

    Setel :sunken="true" untuk komponen yang perlu latar lebih gelap agar
    tepinya terbaca — tombol, chip, apa pun yang memakai material menonjol.
--}}

<div {{ $attributes->class('overflow-hidden rounded-lg border border-line bg-surface-raised') }}>
    @if ($title)
        <div class="border-b border-line px-5 py-3">
            <h4 class="font-display text-sm font-semibold text-ink">{{ $title }}</h4>
        </div>
    @endif

    <div @class([
        'flex flex-wrap items-start gap-3 p-6',
        'bg-surface-sunken' => $sunken,
    ])>
        {{ $slot }}
    </div>

    @if ($code)
        <div x-data="{ copied: false }" class="relative border-t border-line">
            <button type="button"
                    x-on:click="navigator.clipboard.writeText($refs.source.textContent.trim()); copied = true; setTimeout(() => copied = false, 1600)"
                    class="absolute end-3 top-3 flex h-7 items-center gap-1.5 rounded-sm border border-line bg-surface-raised px-2.5 text-[12px] text-ink-secondary transition hover:text-ink focus-visible:outline-none focus-visible:ring-3 focus-visible:ring-accent-soft">
                <template x-if="! copied"><span>Salin</span></template>
                <template x-if="copied"><span class="text-success">Tersalin</span></template>
            </button>

            <pre class="overflow-x-auto bg-code p-5 pe-24 font-mono text-[12.5px] leading-relaxed text-code-ink"><code x-ref="source">{{ trim($code) }}</code></pre>
        </div>
    @endif
</div>
