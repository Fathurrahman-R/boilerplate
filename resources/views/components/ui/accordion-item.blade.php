@props([
    'title',
    'key' => null,
])

@php($key ??= Str::slug($title).'-'.Str::random(4))

{{-- Bukaannya memakai grid 0fr→1fr, bukan max-height tebakan: tingginya
     mengikuti isi sebenarnya, jadi teks panjang tidak pernah terpotong. --}}

<div class="border-b border-line" x-data="{
        get expanded() { return this.open === @js($key) },
        toggle() { this.open = this.expanded ? null : @js($key) },
     }">
    <button type="button" x-on:click="toggle()" :aria-expanded="expanded"
            class="flex w-full items-center gap-3 py-[13px] text-start text-[14.5px] font-medium text-ink transition hover:text-accent">
        <span class="flex-1">{{ $title }}</span>
        <span class="flex shrink-0 transition-transform duration-200" :class="expanded && 'rotate-180'">
            <x-ui.icon name="chevron-down" class="size-4 text-ink-muted" />
        </span>
    </button>

    <div class="grid transition-[grid-template-rows] duration-240 ease-rizz"
         :class="expanded ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
        <div class="overflow-hidden">
            <div class="pb-3.5 text-base2 text-ink-secondary">{{ $slot }}</div>
        </div>
    </div>
</div>
