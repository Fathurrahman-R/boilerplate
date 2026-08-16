@php use App\Models\Post; @endphp

<div class="flex flex-col gap-5">
    <div class="flex items-start justify-between gap-3">
        <h4 class="font-display text-base font-semibold text-ink">{{ $post->title }}</h4>
        <x-ui.badge :variant="$post->status->variant()">{{ $post->status->label() }}</x-ui.badge>
    </div>

    @if ($post->excerpt)
        <p class="text-base2 text-ink-secondary">{{ $post->excerpt }}</p>
    @endif

    <dl class="flex flex-col gap-3.5 text-base2">
        <div class="flex gap-3.5">
            <dt class="w-[110px] shrink-0 text-ink-muted">Penulis</dt>
            <dd class="text-ink">{{ $post->author?->name ?? '—' }}</dd>
        </div>

        <div class="flex gap-3.5">
            <dt class="w-[110px] shrink-0 text-ink-muted">Terbit</dt>
            <dd class="text-ink">{{ $post->published_at?->translatedFormat('d M Y H:i') ?? '—' }}</dd>
        </div>

        <div class="flex gap-3.5">
            <dt class="w-[110px] shrink-0 text-ink-muted">Dibuat</dt>
            <dd class="text-ink">{{ $post->created_at?->translatedFormat('d M Y H:i') }}</dd>
        </div>
    </dl>

    <div class="flex flex-wrap gap-2 border-t border-line pt-4">
        @can('update', $post)
            <x-ui.button :href="route('admin.posts.edit', $post)" size="sm">
                <x-ui.icon name="pencil" class="size-4" />
                Ubah artikel
            </x-ui.button>
        @endcan
    </div>
</div>
