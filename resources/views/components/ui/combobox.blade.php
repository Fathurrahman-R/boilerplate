@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Ketik untuk mencari',
    'hint' => null,
    'empty' => 'Tidak ada yang cocok.',
    'required' => false,
])

{{--
    Pilihan tunggal dari daftar yang terlalu panjang untuk sebuah select.
    Menyaring saat mengetik; Enter memilih hasil teratas, Esc menutup daftar.

    $options berbentuk ['nilai' => 'Label']. Seluruh daftar dikirim ke halaman
    dan disaring di sisi klien — cocok sampai ratusan baris, bukan ribuan.
--}}

@php
    $errorKey = str_replace(['[', ']'], ['.', ''], $name);
    $invalid = $errors->has($errorKey);
    $id = $attributes->get('id', $name);
    $current = old($errorKey, $selected);

    $items = collect($options)
        ->map(fn ($label, $value): array => ['value' => (string) $value, 'label' => (string) $label])
        ->values();
@endphp

<div x-data="{
        items: @js($items),
        picked: @js($current === null ? '' : (string) $current),
        query: '',
        open: false,
        active: 0,
        get results() {
            const q = this.query.trim().toLowerCase();
            return q === '' ? this.items : this.items.filter(i => i.label.toLowerCase().includes(q));
        },
        labelFor(value) {
            return this.items.find(i => i.value === value)?.label ?? '';
        },
        choose(item) {
            this.picked = item.value;
            this.query = item.label;
            this.open = false;
        },
        enter() {
            const hit = this.results[this.active];
            if (hit) this.choose(hit);
        },
        move(by) {
            if (! this.open) { this.open = true; return; }
            const count = this.results.length;
            if (count === 0) return;
            this.active = (this.active + by + count) % count;
        },
     }"
     x-init="query = labelFor(picked)"
     x-on:click.outside="open = false; query = labelFor(picked)"
     class="relative">

    @if ($label)
        <x-ui.label :for="$id" :required="$required" :invalid="$invalid">{{ $label }}</x-ui.label>
    @endif

    <input type="hidden" name="{{ $name }}" :value="picked">

    <input type="text" id="{{ $id }}"
           x-model="query"
           x-on:focus="open = true; active = 0"
           x-on:input="open = true; active = 0"
           x-on:keydown.arrow-down.prevent="move(1)"
           x-on:keydown.arrow-up.prevent="move(-1)"
           x-on:keydown.enter.prevent="enter()"
           x-on:keydown.escape="open = false"
           placeholder="{{ $placeholder }}"
           autocomplete="off"
           role="combobox" aria-autocomplete="list" :aria-expanded="open"
           @if ($invalid) aria-invalid="true" @endif
           {{ $attributes->class([
               'block h-control w-full rounded-md border-[0.5px] bg-fill-4 px-3 text-base text-ink',
               'outline-none transition-colors duration-[--dur-fast] placeholder:text-ink-quaternary',
               'focus:bg-surface-raised focus:shadow-[var(--focus-ring)]',
               'border-line' => ! $invalid,
               'border-danger' => $invalid,
           ]) }}>

    <div x-show="open" x-cloak
         x-spring="{ from: { opacity: 0, scale: 0.97, y: -4 },
                     to:   { opacity: 1, scale: 1,    y: 0 },
                     token: 'sheet', exitToken: 'overlay', origin: 'bottom-start' }"
         data-mat="thin"
         class="material absolute inset-x-0 z-50 mt-1.5 max-h-52 overflow-y-auto rounded-lg p-1.5"
         role="listbox">
        <template x-for="(item, index) in results" :key="item.value">
            <button type="button"
                    x-on:click="choose(item)"
                    x-on:mouseenter="active = index"
                    class="flex w-full items-center gap-2.5 rounded-sm px-2.5 py-2 text-left text-base transition-colors duration-[--dur-fast]"
                    :class="index === active ? 'bg-fill-3 text-ink' : 'text-ink-secondary'"
                    :aria-selected="item.value === picked" role="option">
                <span x-text="item.label"></span>
            </button>
        </template>

        <p x-show="results.length === 0" class="px-2.5 py-2 text-[13.5px] text-ink-muted">{{ $empty }}</p>
    </div>

    <x-ui.field-note :error="$invalid ? $errors->first($errorKey) : null" :hint="$hint" />
</div>
