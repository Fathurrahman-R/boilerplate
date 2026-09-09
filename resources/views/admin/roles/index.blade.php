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

    <x-ui.table :table="$table"
                :selectable="$roles->reject(fn ($role) => $role->is_locked || $role->isSuperAdmin())->pluck('id')->all()"
 openable
                :headers="['name' => 'Nama', 0 => 'Label', 1 => 'Permission', 2 => 'Pengguna', 3 => '']">
        <x-slot:toolbar>
            <x-ui.table.toolbar :table="$table" placeholder="Cari role…">
                <x-slot:bulk>
                    <x-can :resource="rk('roles', ResourceAction::Delete)">
                        <form method="POST" action="{{ route('admin.roles.bulk-destroy') }}">
                            @csrf
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="ids[]" :value="id">
                            </template>

                            <x-ui.button type="submit" variant="secondary" size="sm" class="border-danger text-danger">
                                <x-ui.icon name="trash-2" class="size-4" />
                                Hapus terpilih
                            </x-ui.button>
                        </form>
                    </x-can>
                </x-slot:bulk>
            </x-ui.table.toolbar>
        </x-slot:toolbar>

        @forelse ($roles as $role)
            <x-ui.table.row :id="$role->is_locked || $role->isSuperAdmin() ? null : $role->id"
 selectable
                            :panel="route('admin.roles.panel', $role)">
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
                    <div class="flex justify-end gap-1" data-row-action>
                        <x-can :resource="rk('roles', ResourceAction::Update)">
                            <x-ui.button :href="route('admin.roles.edit', $role)" variant="secondary" size="xs" title="Ubah">
                                <x-ui.icon name="pencil" class="h-4 w-4" />
                            </x-ui.button>
                        </x-can>

                        @if (! $role->is_locked && ! $role->isSuperAdmin())
                            <x-can :resource="rk('roles', ResourceAction::Delete)">
                                <x-ui.button type="button" variant="secondary" size="xs" title="Hapus"
 x-on:click="$dispatch('confirm-delete', { url: '{{ route('admin.roles.destroy', $role) }}', name: {{ Js::from($role->name) }} })">
                                    <x-ui.icon name="trash-2" class="h-4 w-4 text-danger" />
                                </x-ui.button>

                            </x-can>
                        @endif
                    </div>
                </x-ui.table.cell>
            </x-ui.table.row>
        @empty
            <tr>
                <td colspan="7">
                    <x-ui.empty-state title="Belum ada role" />
                </td>
            </tr>
        @endforelse
        <x-slot:footer>{{ $roles->links() }}</x-slot:footer>
    </x-ui.table>

    <x-ui.confirm-delete />
</x-layouts.admin>
