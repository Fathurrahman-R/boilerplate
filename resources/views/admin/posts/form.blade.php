@php($post ??= null)

{{--
    Isi artikel butuh lebar penuh, jadi labelnya naik ke atas lewat prop
    `stacked`. Yang tetap sebaris hanya field pendek — di situ label di kiri
    justru menghemat setengah tinggi grup.
--}}

<div class="measure flex flex-col gap-7">
    <x-ui.list title="Isi artikel" hint="Ringkasan tampil di daftar artikel. Maksimal 500 karakter.">
        <x-ui.form-row label="Judul" for="title" required>
            <x-ui.input name="title" id="title" required :value="$post?->title" aria-label="Judul" />
        </x-ui.form-row>

        <x-ui.form-row label="Ringkasan" for="excerpt" stacked>
            <x-ui.textarea name="excerpt" id="excerpt" :value="$post?->excerpt" rows="2" aria-label="Ringkasan" />
        </x-ui.form-row>

        <x-ui.form-row label="Isi" for="body" stacked>
            <x-ui.textarea name="body" id="body" :value="$post?->body" rows="14" aria-label="Isi" />
        </x-ui.form-row>
    </x-ui.list>

    <x-ui.list title="Publikasi"
               :hint="$post?->published_at
                   ? 'Terbit sejak '.$post->published_at->translatedFormat('d F Y, H:i').'.'
                   : null">
        <x-ui.form-row label="Status" for="status" required>
            <x-ui.select name="status" id="status" :options="$statuses"
                         :selected="$post?->status->value ?? 'draft'" required aria-label="Status" />
        </x-ui.form-row>
    </x-ui.list>
</div>
