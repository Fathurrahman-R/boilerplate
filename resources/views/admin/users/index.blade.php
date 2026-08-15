@php use App\Enums\ResourceAction; @endphp

<x-layouts.admin heading="Pengguna"
                 description="Kelola akun dan role yang dimilikinya."
                 :breadcrumb="['Pengguna' => null]">
    <x-slot:actions>
        <x-can :resource="rk('users', ResourceAction::Export)">
            <x-ui.button :href="route('admin.users.export', request()->query())" variant="secondary" size="sm">
                <x-ui.icon name="download" class="h-4 w-4" />
                Ekspor CSV
            </x-ui.button>
        </x-can>

        <x-can :resource="rk('users', ResourceAction::Create)">
            <x-ui.button :href="route('admin.users.create')" size="sm">
                <x-ui.icon name="plus" class="h-4 w-4" />
                Tambah pengguna
            </x-ui.button>
        </x-can>
    </x-slot:actions>

    <div class="mb-4">
        <x-ui.table.toolbar :table="$table" placeholder="Cari nama atau email…">
            <x-slot:filters>
                <select name="role" class="rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua role</option>
                    @foreach ($roles as $value => $label)
                        <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                    @endforeach
                </select>

                <select name="status" class="rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua status</option>
                    <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
                </select>
            </x-slot:filters>
        </x-ui.table.toolbar>
    </div>

    <x-ui.table :table="$table" :headers="['name' => 'Nama', 'email' => 'Email', 0 => 'Role', 1 => 'Status', 'created_at' => 'Dibuat', 2 => '']">
        @forelse ($users as $user)
            <x-ui.table.row>
                <x-ui.table.cell header>
                    <div class="flex items-center gap-3">
                        <x-ui.avatar :user="$user" size="sm" />
                        {{ $user->name }}
                    </div>
                </x-ui.table.cell>

                <x-ui.table.cell>{{ $user->email }}</x-ui.table.cell>

                <x-ui.table.cell>
                    <div class="flex flex-wrap gap-1">
                        @forelse ($user->roles as $role)
                            <x-ui.badge :variant="$role->isSuperAdmin() ? 'purple' : 'primary'">{{ $role->displayName() }}</x-ui.badge>
                        @empty
                            <span class="text-gray-400">—</span>
                        @endforelse
                    </div>
                </x-ui.table.cell>

                <x-ui.table.cell>
                    <x-ui.badge :variant="$user->is_active ? 'success' : 'danger'" dot>
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </x-ui.badge>
                </x-ui.table.cell>

                <x-ui.table.cell>{{ $user->created_at?->translatedFormat('d M Y') }}</x-ui.table.cell>

                <x-ui.table.cell align="right">
                    <div class="flex justify-end gap-1">
                        <x-can :resource="rk('users', ResourceAction::Update)">
                            <x-ui.button :href="route('admin.users.edit', $user)" variant="ghost" size="xs" title="Ubah">
                                <x-ui.icon name="pencil" class="h-4 w-4" />
                            </x-ui.button>
                        </x-can>

                        <x-can :resource="rk('users', ResourceAction::Delete)">
                            <x-ui.button variant="ghost" size="xs" title="Hapus"
                                         data-modal-target="hapus-user-{{ $user->id }}"
                                         data-modal-toggle="hapus-user-{{ $user->id }}">
                                <x-ui.icon name="trash" class="h-4 w-4 text-red-600" />
                            </x-ui.button>

                            <x-ui.modal :id="'hapus-user-'.$user->id" title="Hapus pengguna" size="sm">
                                Yakin menghapus <strong>{{ $user->name }}</strong>? Tindakan ini tidak bisa dibatalkan.

                                <x-slot:footer>
                                    <x-ui.button variant="secondary" type="button" data-modal-hide="hapus-user-{{ $user->id }}">Batal</x-ui.button>

                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button variant="danger" type="submit">Hapus</x-ui.button>
                                    </form>
                                </x-slot:footer>
                            </x-ui.modal>
                        </x-can>
                    </div>
                </x-ui.table.cell>
            </x-ui.table.row>
        @empty
            <tr>
                <td colspan="6">
                    <x-ui.empty-state title="Tidak ada pengguna" description="Ubah kata kunci pencarian atau tambahkan pengguna baru." />
                </td>
            </tr>
        @endforelse
    </x-ui.table>

    <div class="mt-4">{{ $users->links() }}</div>
</x-layouts.admin>
