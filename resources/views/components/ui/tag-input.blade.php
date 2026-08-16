@props([
    'name',
    'label' => null,
    'value' => [],
    'placeholder' => 'Tambah lalu tekan Enter…',
    'hint' => null,
])

{{--
    Daftar nilai pendek yang jumlahnya bebas — penerima, label, kata kunci.

    Enter atau koma menambahkan. Backspace pada kolom kosong menghapus tag
    terakhir. Nilainya terkirim sebagai array: name[].
--}}

@php
    $errorKey = str_replace(['[', ']'], ['.', ''], $name);
    $invalid = $errors->has($errorKey);
    $id = $attributes->get('id', $name);
    $current = array_values((array) old($errorKey, $value));
@endphp

<div x-data="{
        tags: @js($current),
        draft: '',
        add() {
            const value = this.draft.trim().replace(/,$/, '');
            if (value !== '' && ! this.tags.includes(value)) this.tags.push(value);
            this.draft = '';
        },
        backspace() {
            if (this.draft === '') this.tags.pop();
        },
     }">
    @if ($label)
        <x-ui.label :for="$id" :invalid="$invalid">{{ $label }}</x-ui.label>
    @endif

    <template x-for="tag in tags" :key="tag">
        <input type="hidden" name="{{ $name }}[]" :value="tag">
    </template>

    <div class="flex min-h-control flex-wrap items-center gap-1.5 rounded-md border bg-surface-sunken p-1.5 shadow-well transition focus-within:border-accent focus-within:ring-3 focus-within:ring-accent-soft {{ $invalid ? 'border-danger' : 'border-line' }}"
         x-on:click="$refs.draft.focus()">

        <template x-for="(tag, index) in tags" :key="tag">
            <span class="inline-flex items-center gap-1.5 rounded-full border border-line bg-[image:var(--mat-raised)] py-1 ps-2.5 pe-1.5 text-[12.5px] text-ink shadow-[var(--bevel),var(--lift)]">
                <span x-text="tag"></span>
                <button type="button" x-on:click.stop="tags.splice(index, 1)"
                        class="flex text-ink-muted transition hover:text-danger">
                    <span class="sr-only">Hapus</span>
                    <x-ui.icon name="x" class="size-3.5" />
                </button>
            </span>
        </template>

        <input type="text" id="{{ $id }}" x-ref="draft" x-model="draft"
               x-on:keydown.enter.prevent="add()"
               x-on:keydown="if ($event.key === ',') { $event.preventDefault(); add() }"
               x-on:keydown.backspace="backspace()"
               x-on:blur="add()"
               placeholder="{{ $placeholder }}"
               class="h-7 min-w-32 flex-1 border-0 bg-transparent px-1.5 text-[13.5px] text-ink outline-none placeholder:text-ink-muted"
               {{ $attributes }}>
    </div>

    <x-ui.field-note :error="$invalid ? $errors->first($errorKey) : null" :hint="$hint" />
</div>
