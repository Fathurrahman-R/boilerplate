@php($post ??= null)

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <x-ui.card title="Isi artikel">
            <div class="space-y-4">
                <x-ui.input name="title" label="Judul" :value="$post?->title" required />

                <x-ui.textarea name="excerpt" label="Ringkasan" :value="$post?->excerpt" rows="2"
                               hint="Tampil di daftar artikel. Maksimal 500 karakter." />

                <x-ui.textarea name="body" label="Isi" :value="$post?->body" rows="12" />
            </div>
        </x-ui.card>
    </div>

    <div>
        <x-ui.card title="Publikasi">
            <x-ui.select name="status" label="Status" :options="$statuses" :selected="$post?->status->value ?? 'draft'" required />

            @if ($post?->published_at)
                <p class="mt-3 text-sm text-ink-muted">
                    Terbit sejak {{ $post->published_at->translatedFormat('d F Y, H:i') }}.
                </p>
            @endif
        </x-ui.card>
    </div>
</div>
