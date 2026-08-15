@php use App\Enums\ResourceAction; @endphp

<x-layouts.admin heading="Role"
                 description="Sekumpulan permission yang bisa ditugaskan ke pengguna."
                 :breadcrumb="['Role' => null]">
    <x-slot:actions>
        <x-can :resource="rk('roles', ResourceAction::Create)">
            <x-ui.button :href="route('admin.roles.create')" size="sm">
                <x-ui.icon name="plus" class="h-4 w-4" />
                Tambah role
            </x-ui.button>
        </x-can>
    </x-slot:actions>

    <div class="mb-4">
        <x-ui.table.toolbar :table="$table" placeholder="Cari role…" />
    </div>

    <x-ui.table :table="$table" :headers="['name' => 'Nama', 0 => 'Label', 1 => 'Permission', 2 => 'Pengguna', 3 => '']">
        @forelse ($roles as $role)
            <x-ui.table.row>
                <x-ui.table.cell header>
                    <div class="flex items-center gap-2">
                        {{ $role->name }}
                        @if ($role->isSuperAdmin())
                            <x-ui.badge variant="purple" pill>super admin</x-ui.badge>
                        @endif
                    </div>
                </x-ui.table.cell>

                <x-ui.table.cell>{{ $role->label ?: '—' }}</x-ui.table.cell>
                <x-ui.table.cell>{{ $role->isSuperAdmin() ? 'semua' : $role->permissions_count }}</x-ui.table.cell>
                <x-ui.table.cell>{{ $role->users_count }}</x-ui.table.cell>

                <x-ui.table.cell align="right">
                    <div class="flex justify-end gap-1">
                        <x-can :resource="rk('roles', ResourceAction::Update)">
                            <x-ui.button :href="route('admin.roles.edit', $role)" variant="ghost" size="xs" title="Ubah">
                                <x-ui.icon name="pencil" class="h-4 w-4" />
                            </x-ui.button>
                        </x-can>

                        @if (! $role->is_locked && ! $role->isSuperAdmin())
                            <x-can :resource="rk('roles', ResourceAction::Delete)">
                                <x-ui.button variant="ghost" size="xs" title="Hapus"
                                             data-modal-target="hapus-role-{{ $role->id }}"
                                             data-modal-toggle="hapus-role-{{ $role->id }}">
                                    <x-ui.icon name="trash" class="h-4 w-4 text-red-600" />
                                </x-ui.button>

                                <x-ui.modal :id="'hapus-role-'.$role->id" title="Hapus role" size="sm">
                                    Yakin menghapus role <strong>{{ $role->name }}</strong>?
                                    {{ $role->users_count }} pengguna akan kehilangan permission dari role ini.

                                    <x-slot:footer>
                                        <x-ui.button variant="secondary" type="button" data-modal-hide="hapus-role-{{ $role->id }}">Batal</x-ui.button>

                                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button variant="danger" type="submit">Hapus</x-ui.button>
                                        </form>
                                    </x-slot:footer>
                                </x-ui.modal>
                            </x-can>
                        @endif
                    </div>
                </x-ui.table.cell>
            </x-ui.table.row>
        @empty
            <tr>
                <td colspan="5">
                    <x-ui.empty-state title="Belum ada role" />
                </td>
            </tr>
        @endforelse
    </x-ui.table>

    <div class="mt-4">{{ $roles->links() }}</div>
</x-layouts.admin>
