@props([
    'code' => null,
    'language' => null,
    'copyable' => true,
])

@php($code ??= trim($slot->toHtml()))

<div x-data="{ copied: false,
        copy() {
            navigator.clipboard?.writeText(this.$refs.source.textContent.trim());
            this.copied = true;
            setTimeout(() => this.copied = false, 1600);
        } }"
     {{ $attributes->class('relative') }}>

    @if ($copyable)
        <button type="button" x-on:click="copy()"
                class="absolute end-2.5 top-2.5 inline-flex h-7 items-center gap-1.5 rounded-sm border border-line-strong bg-[image:var(--mat-raised)] px-2.5 font-mono text-[11px] text-ink-secondary shadow-[var(--bevel),var(--lift)] transition hover:brightness-95 active:translate-y-px active:shadow-press focus-visible:ring-3 focus-visible:ring-accent-soft focus-visible:outline-none">
            <x-ui.icon name="copy" class="size-3.5" x-show="! copied" />
            <x-ui.icon name="check" class="size-3.5 text-success" x-show="copied" x-cloak />
            <span x-text="copied ? 'Tersalin' : 'Salin'">Salin</span>
        </button>
    @endif

    <pre class="overflow-x-auto rounded-md border border-line bg-code px-5 py-[18px] font-mono text-sm2 leading-[1.75] text-code-ink"><code x-ref="source" @if ($language) data-language="{{ $language }}" @endif>{{ $code }}</code></pre>
</div>
