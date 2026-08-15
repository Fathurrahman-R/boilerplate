@php use App\Enums\ResourceAction; @endphp

<x-layouts.admin heading="Permission"
                 description="Izin mentah yang dibagikan ke role. Resource key menunjuk ke sini lewat pemetaan."
                 :breadcrumb="['Permission' => null]">
    <x-slot:actions>
        <x-can :resource="rk('permissions', ResourceAction::Create)">
            <x-ui.button :href="route('admin.permissions.create')" size="sm">
                <x-ui.icon name="plus" class="h-4 w-4" />
                Tambah permission
            </x-ui.button>
        </x-can>
    </x-slot:actions>

    <div class="mb-4">
        <x-ui.table.toolbar :table="$table" placeholder="Cari nama permission…">
            <x-slot:filters>
                <select name="group" class="rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua grup</option>
                    @foreach ($groups as $value => $label)
                        <option value="{{ $value }}" @selected(request('group') === $value)>{{ $label }}</option>
                    @endforeach
                </select>

                <select name="status" class="rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua</option>
                    <option value="dipakai" @selected(request('status') === 'dipakai')>Dipakai resource key</option>
                    <option value="yatim" @selected(request('status') === 'yatim')>Tidak dipakai key mana pun</option>
                </select>
            </x-slot:filters>
        </x-ui.table.toolbar>
    </div>

    <x-ui.table :table="$table" :headers="['name' => 'Nama', 'group' => 'Grup', 0 => 'Dipetakan dari key', 1 => 'Role', 2 => '']">
        @forelse ($permissions as $permission)
            <x-ui.table.row>
                <x-ui.table.cell header>
                    <div class="flex items-center gap-2">
                        <code>{{ $permission->name }}</code>
                        @if ($permission->is_locked)
                            <x-ui.badge variant="warning" pill>inti</x-ui.badge>
                        @endif
                    </div>

                    @if ($permission->label)
                        <span class="block text-xs font-normal text-gray-500 dark:text-gray-400">{{ $permission->label }}</span>
                    @endif
                </x-ui.table.cell>

                <x-ui.table.cell>{{ $permission->group ?: '—' }}</x-ui.table.cell>

                <x-ui.table.cell>
                    @if ($permission->mappings_count === 0)
                        <x-ui.badge variant="warning">tidak dipakai key</x-ui.badge>
                    @else
                        <div class="flex flex-wrap gap-1">
                            @foreach ($permission->mappings as $mapping)
                                <code class="rounded bg-gray-100 px-1.5 py-0.5 text-xs dark:bg-gray-700">{{ $mapping->key() }}</code>
                            @endforeach
                        </div>
                    @endif
                </x-ui.table.cell>

                <x-ui.table.cell>{{ $permission->roles_count }}</x-ui.table.cell>

                <x-ui.table.cell align="right">
                    <div class="flex justify-end gap-1">
                        <x-can :resource="rk('permissions', ResourceAction::Update)">
                            <x-ui.button :href="route('admin.permissions.edit', $permission)" variant="ghost" size="xs" title="Ubah">
                                <x-ui.icon name="pencil" class="h-4 w-4" />
                            </x-ui.button>
                        </x-can>

                        @unless ($permission->is_locked)
                            <x-can :resource="rk('permissions', ResourceAction::Delete)">
                                <x-ui.button variant="ghost" size="xs" title="Hapus"
                                             data-modal-target="hapus-permission-{{ $permission->id }}"
                                             data-modal-toggle="hapus-permission-{{ $permission->id }}">
                                    <x-ui.icon name="trash" class="h-4 w-4 text-red-600" />
                                </x-ui.button>

                                <x-ui.modal :id="'hapus-permission-'.$permission->id" title="Hapus permission">
                                    <p>Yakin menghapus <code>{{ $permission->name }}</code>?</p>

                                    @if ($permission->mappings_count > 0)
                                        <x-ui.alert variant="warning">
                                            {{ $permission->mappings_count }} resource key menunjuk permission ini.
                                            Key-nya tidak ikut terhapus, tapi berubah jadi tak terpetakan — dan aksesnya
                                            langsung tertutup untuk semua orang kecuali super admin.
                                        </x-ui.alert>
                                    @endif

                                    @if ($permission->roles_count > 0)
                                        <p class="text-gray-500 dark:text-gray-400">
                                            {{ $permission->roles_count }} role kehilangan izin ini.
                                        </p>
                                    @endif

                                    <x-slot:footer>
                                        <x-ui.button variant="secondary" type="button" data-modal-hide="hapus-permission-{{ $permission->id }}">Batal</x-ui.button>

                                        <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}">
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
                    <x-ui.empty-state title="Belum ada permission" />
                </td>
            </tr>
        @endforelse
    </x-ui.table>

    <div class="mt-4">{{ $permissions->links() }}</div>
</x-layouts.admin>
