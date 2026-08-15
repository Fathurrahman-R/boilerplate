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

    <div class="mb-4">
        <x-ui.table.toolbar :table="$table" placeholder="Cari judul…">
            <x-slot:filters>
                <select name="status" class="rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua status</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </x-slot:filters>
        </x-ui.table.toolbar>
    </div>

    <x-ui.table :table="$table" :headers="['title' => 'Judul', 0 => 'Penulis', 'status' => 'Status', 'published_at' => 'Terbit', 1 => '']">
        @forelse ($posts as $post)
            <x-ui.table.row>
                <x-ui.table.cell header>
                    {{ $post->title }}
                    @if ($post->excerpt)
                        <span class="block max-w-md truncate text-xs font-normal text-gray-500 dark:text-gray-400">{{ $post->excerpt }}</span>
                    @endif
                </x-ui.table.cell>

                <x-ui.table.cell>{{ $post->author?->name ?? '—' }}</x-ui.table.cell>

                <x-ui.table.cell>
                    <x-ui.badge :variant="$post->status->variant()">{{ $post->status->label() }}</x-ui.badge>
                </x-ui.table.cell>

                <x-ui.table.cell>{{ $post->published_at?->translatedFormat('d M Y') ?? '—' }}</x-ui.table.cell>

                <x-ui.table.cell align="right">
                    <div class="flex justify-end gap-1">
                        @can('update', $post)
                            <x-ui.button :href="route('admin.posts.edit', $post)" variant="ghost" size="xs" title="Ubah">
                                <x-ui.icon name="pencil" class="h-4 w-4" />
                            </x-ui.button>
                        @endcan

                        @can('delete', $post)
                            <x-ui.button variant="ghost" size="xs" title="Hapus"
                                         data-modal-target="hapus-post-{{ $post->id }}"
                                         data-modal-toggle="hapus-post-{{ $post->id }}">
                                <x-ui.icon name="trash" class="h-4 w-4 text-red-600" />
                            </x-ui.button>

                            <x-ui.modal :id="'hapus-post-'.$post->id" title="Hapus artikel" size="sm">
                                Yakin menghapus <strong>{{ $post->title }}</strong>?

                                <x-slot:footer>
                                    <x-ui.button variant="secondary" type="button" data-modal-hide="hapus-post-{{ $post->id }}">Batal</x-ui.button>

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
                <td colspan="5">
                    <x-ui.empty-state title="Belum ada artikel" />
                </td>
            </tr>
        @endforelse
    </x-ui.table>

    <div class="mt-4">{{ $posts->links() }}</div>
</x-layouts.admin>
