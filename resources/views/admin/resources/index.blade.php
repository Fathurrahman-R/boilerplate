@php use App\Enums\ResourceAction; @endphp

<x-layouts.admin heading="Resource"
                 description="Setiap resource menghasilkan resource key berbentuk {resource}.{aksi} yang dipakai di route, tampilan, dan menu."
                 :breadcrumb="['Resource' => null]">
    <x-slot:actions>
        <x-can :resource="rk('resources', ResourceAction::Create)">
            <x-ui.button :href="route('admin.resources.create')" size="sm">
                <x-ui.icon name="plus" class="h-4 w-4" />
                Tambah resource
            </x-ui.button>
        </x-can>
    </x-slot:actions>

    <div class="mb-4">
        <x-ui.table.toolbar :table="$table" placeholder="Cari resource…">
            <x-slot:filters>
                <select name="group" class="rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua grup</option>
                    @foreach ($groups as $value => $label)
                        <option value="{{ $value }}" @selected(request('group') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </x-slot:filters>
        </x-ui.table.toolbar>
    </div>

    <x-ui.table :table="$table" :headers="['key' => 'Key', 'label' => 'Label', 'group' => 'Grup', 0 => 'Aksi', 1 => '']">
        @forelse ($resources as $resource)
            <x-ui.table.row>
                <x-ui.table.cell header>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.resources.show', $resource) }}" class="hover:underline">
                            <code>{{ $resource->key }}</code>
                        </a>

                        @if ($resource->is_locked)
                            <x-ui.badge variant="warning" pill>inti</x-ui.badge>
                        @endif
                    </div>
                </x-ui.table.cell>

                <x-ui.table.cell>{{ $resource->label }}</x-ui.table.cell>
                <x-ui.table.cell>{{ $resource->group ?: '—' }}</x-ui.table.cell>

                <x-ui.table.cell>
                    @php($unmapped = $resource->mappings->whereNull('permission_id')->count())

                    <div class="flex flex-wrap items-center gap-1">
                        <x-ui.badge variant="primary">{{ $resource->mappings_count }} aksi</x-ui.badge>

                        @if ($unmapped > 0)
                            <x-ui.badge variant="danger" dot>{{ $unmapped }} belum dipetakan</x-ui.badge>
                        @endif
                    </div>
                </x-ui.table.cell>

                <x-ui.table.cell align="right">
                    <div class="flex justify-end gap-1">
                        <x-ui.button :href="route('admin.resources.show', $resource)" variant="ghost" size="xs" title="Detail">
                            <x-ui.icon name="eye" class="h-4 w-4" />
                        </x-ui.button>

                        <x-can :resource="rk('resources', ResourceAction::Update)">
                            <x-ui.button :href="route('admin.resources.edit', $resource)" variant="ghost" size="xs" title="Ubah">
                                <x-ui.icon name="pencil" class="h-4 w-4" />
                            </x-ui.button>
                        </x-can>

                        @unless ($resource->is_locked)
                            <x-can :resource="rk('resources', ResourceAction::Delete)">
                                <x-ui.button variant="ghost" size="xs" title="Hapus"
                                             data-modal-target="hapus-resource-{{ $resource->id }}"
                                             data-modal-toggle="hapus-resource-{{ $resource->id }}">
                                    <x-ui.icon name="trash" class="h-4 w-4 text-red-600" />
                                </x-ui.button>

                                <x-ui.modal :id="'hapus-resource-'.$resource->id" title="Hapus resource">
                                    <p>Yakin menghapus <code>{{ $resource->key }}</code> beserta {{ $resource->mappings_count }} pemetaannya?</p>

                                    <x-ui.alert variant="warning">
                                        Permission-nya tidak ikut dihapus — bisa jadi masih dipakai key lain. Cek daftar
                                        permission setelah ini kalau ingin membersihkannya.
                                    </x-ui.alert>

                                    <x-slot:footer>
                                        <x-ui.button variant="secondary" type="button" data-modal-hide="hapus-resource-{{ $resource->id }}">Batal</x-ui.button>

                                        <form method="POST" action="{{ route('admin.resources.destroy', $resource) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button variant="danger" type="submit">Hapus</x-ui.button>
                                        </form>
                                    </x-slot:footer>
                                </x-ui.modal>
                            </x-can>
                        @endunless
                    </div>
                </x-ui.table.cell>
            </x-ui.table.row>
        @empty
            <tr>
                <td colspan="5">
                    <x-ui.empty-state title="Belum ada resource"
                                      description="Buat resource pertama untuk mulai memakai resource key." />
                </td>
            </tr>
        @endforelse
    </x-ui.table>

    <div class="mt-4">{{ $resources->links() }}</div>
</x-layouts.admin>
