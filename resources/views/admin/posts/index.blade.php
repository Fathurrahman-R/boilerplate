@php use App\Models\Post; @endphp

<x-layouts.admin heading="Artikel"
                 description="Modul contoh. Salin strukturnya saat membuat modul baru."
                 :breadcrumb="['Artikel' => null]">
    <x-slot:actions>
        @can('export', Post::class)
            <x-ui.button :href="route('admin.posts.export', request()->query())" variant="secondary" size="sm">
                <x-ui.icon name="download" class="h-4 w-4" />
                Ekspor CSV
            </x-ui.button>
        @endcan

        @can('create', Post::class)
            <x-ui.button :href="route('admin.posts.create')" size="sm">
                <x-ui.icon name="plus" class="h-4 w-4" />
                Tulis artikel
            </x-ui.button>
        @endcan
    </x-slot:actions>

    <x-ui.table :table="$table"
                :selectable="$posts->pluck('id')->all()"
                openable
                :headers="['title' => 'Judul', 0 => 'Penulis', 'status' => 'Status', 'published_at' => 'Terbit', 1 => '']">
        <x-slot:toolbar>
            <x-ui.table.toolbar :table="$table" placeholder="Cari judul…">
                <x-slot:chips>
                    <x-ui.filter-chips param="status" all="Semua status" :options="$statuses" />
                </x-slot:chips>

                @can('delete', Post::class)
                    <x-slot:bulk>
                        <form method="POST" action="{{ route('admin.posts.bulk-destroy') }}">
                            @csrf
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="ids[]" :value="id">
                            </template>

                            <x-ui.button type="submit" variant="secondary" size="sm" class="border-danger text-danger">
                                <x-ui.icon name="trash-2" class="size-4" />
                                Hapus terpilih
                            </x-ui.button>
                        </form>
                    </x-slot:bulk>
                @endcan
            </x-ui.table.toolbar>
        </x-slot:toolbar>

        @forelse ($posts as $post)
            <x-ui.table.row :id="$post->id" :panel="route('admin.posts.panel', $post)">
                <x-ui.table.cell header>
                    {{ $post->title }}
                    @if ($post->excerpt)
                        <span class="block max-w-md truncate text-xs font-normal text-ink-muted">{{ $post->excerpt }}</span>
                    @endif
                </x-ui.table.cell>

                <x-ui.table.cell>{{ $post->author?->name ?? '—' }}</x-ui.table.cell>

                <x-ui.table.cell>
                    <x-ui.badge :variant="$post->status->variant()">{{ $post->status->label() }}</x-ui.badge>
                </x-ui.table.cell>

                <x-ui.table.cell>{{ $post->published_at?->translatedFormat('d M Y') ?? '—' }}</x-ui.table.cell>

                <x-ui.table.cell align="right">
                    <div class="flex justify-end gap-1" data-row-action>
                        @can('update', $post)
                            <x-ui.button :href="route('admin.posts.edit', $post)" variant="secondary" size="xs" title="Ubah">
                                <x-ui.icon name="pencil" class="h-4 w-4" />
                            </x-ui.button>
                        @endcan

                        @can('delete', $post)
                            <x-ui.button type="button" variant="secondary" size="xs" title="Hapus"
                                         x-on:click="$dispatch('modal-open', 'hapus-post-{{ $post->id }}')">
                                <x-ui.icon name="trash-2" class="h-4 w-4 text-danger" />
                            </x-ui.button>

                            <x-ui.modal :id="'hapus-post-'.$post->id" title="Hapus artikel" size="sm">
                                Yakin menghapus <strong>{{ $post->title }}</strong>?

                                <x-slot:footer>
                                    <x-ui.button variant="secondary" type="button" x-on:click="$dispatch('modal-close', 'hapus-post-{{ $post->id }}')">Batal</x-ui.button>

                                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button variant="danger" type="submit">Hapus</x-ui.button>
                                    </form>
                                </x-slot:footer>
                            </x-ui.modal>
                        @endcan
                    </div>
                </x-ui.table.cell>
            </x-ui.table.row>
        @empty
            <tr>
                <td colspan="7">
                    <x-ui.empty-state title="Belum ada artikel" />
                </td>
            </tr>
        @endforelse
        <x-slot:footer>{{ $posts->links() }}</x-slot:footer>
    </x-ui.table>

    <x-ui.drawer-remote title="Detail artikel" />
</x-layouts.admin>
