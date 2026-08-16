@props([
    'name',
    'label' => null,
    'hint' => null,
    'accept' => null,
    'multiple' => false,
])

@php
    $id = $attributes->get('id', $name);
    $error = $errors->first(str_replace(['[]', '[', ']'], ['', '.', ''], $name));
@endphp

{{--
    Area jatuh berkas: bidang cekung dengan batas putus-putus. Berbeda dari
    <x-ui.file-upload> yang berbentuk kontrol satu baris — yang ini dipakai
    kalau mengunggah adalah tugas utama halamannya.
--}}

<div x-data="{
        files: [],
        over: false,
        pick(list) {
            this.files = Array.from(list).map((file) => file.name);
        },
        drop(event) {
            this.over = false;
            this.$refs.input.files = event.dataTransfer.files;
            this.pick(event.dataTransfer.files);
        },
     }"
     {{ $attributes->except('id')->class('flex flex-col') }}>

    @if ($label)
        <x-ui.label :for="$id" :invalid="(bool) $error">{{ $label }}</x-ui.label>
    @endif

    <label for="{{ $id }}"
           x-on:dragover.prevent="over = true"
           x-on:dragleave.prevent="over = false"
           x-on:drop.prevent="drop($event)"
           class="flex cursor-pointer flex-col items-center gap-[7px] rounded-lg border-[1.5px] border-dashed bg-surface-sunken px-5 py-7 text-center shadow-well transition-colors duration-160"
           :class="over ? 'border-accent' : '{{ $error ? 'border-danger' : 'border-line-strong' }}'">
        <x-ui.icon name="upload" class="size-[22px] text-ink-muted" />

        <span class="text-sm font-medium text-ink">Jatuhkan berkas di sini atau klik untuk memilih</span>

        <span class="text-sm2 text-ink-muted" x-text="files.length ? files.join(', ') : @js($hint ?? 'Maksimal 10 MB per berkas')">
            {{ $hint ?? 'Maksimal 10 MB per berkas' }}
        </span>

        <input type="file" id="{{ $id }}" name="{{ $name }}" x-ref="input"
               @if ($accept) accept="{{ $accept }}" @endif
               @if ($multiple) multiple @endif
               x-on:change="pick($event.target.files)"
               class="sr-only">
    </label>

    <x-ui.field-note :error="$error" />
</div>
