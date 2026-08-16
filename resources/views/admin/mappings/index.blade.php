@php use App\Enums\ResourceAction; @endphp

<x-layouts.admin heading="Pemetaan resource key"
                 description="Menentukan permission mana yang berada di balik tiap resource key. Mengubahnya berlaku seketika di route, tampilan, policy, dan menu — tanpa menyentuh kode."
                 :breadcrumb="['Pemetaan Key' => null]">
    <x-slot:actions>
        <x-can :resource="rk('mappings', ResourceAction::Update)">
            @if ($unmappedCount > 0)
                <form method="POST" action="{{ route('admin.mappings.auto') }}">
                    @csrf
                    <x-ui.button type="submit" size="sm">
                        <x-ui.icon name="link" class="h-4 w-4" />
                        Petakan otomatis {{ $unmappedCount }} key kosong
                    </x-ui.button>
                </form>
            @endif
        </x-can>
    </x-slot:actions>

    @if ($unmappedCount > 0)
        <x-ui.alert variant="warning" class="mb-4">
            {{ $unmappedCount }} key belum menunjuk permission mana pun. Selama masih kosong, aksesnya tertutup untuk
            semua orang kecuali super admin.
        </x-ui.alert>
    @endif

    <x-ui.table :table="$table" :headers="[0 => 'Resource key', 'action' => 'Aksi', 1 => 'Permission', 2 => '']">
        <x-slot:toolbar>
            <x-ui.table.toolbar :table="$table" placeholder="Cari key atau permission…">
                <x-slot:filters>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="mapped" @selected(request('status') === 'mapped')>Sudah dipetakan</option>
                        <option value="unmapped" @selected(request('status') === 'unmapped')>Belum dipetakan</option>
                    </select>
                </x-slot:filters>
            </x-ui.table.toolbar>
        </x-slot:toolbar>

        @forelse ($mappings as $mapping)
            <x-ui.table.row>
                <x-ui.table.cell header>
                    <code>{{ $mapping->key() }}</code>
                </x-ui.table.cell>

                <x-ui.table.cell>{{ $mapping->action->label() }}</x-ui.table.cell>

                <x-ui.table.cell>
                    <x-can :resource="rk('mappings', ResourceAction::Update)">
                        <form method="POST" action="{{ route('admin.mappings.update', $mapping) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PUT')

                            <select name="permission_id"
                                    class="form-select w-full max-w-xs">
                                <option value="">— tidak dipetakan (akses tertutup) —</option>
                                @foreach ($permissions as $id => $name)
                                    <option value="{{ $id }}" @selected($mapping->permission_id === $id)>{{ $name }}</option>
                                @endforeach
                            </select>

                            <x-ui.button type="submit" variant="secondary" size="xs">Simpan</x-ui.button>
                        </form>
                    </x-can>

                    {{-- Tanpa izin mengubah, pemetaannya hanya ditampilkan. --}}
                    @unless (resource_allows(rk('mappings', ResourceAction::Update)))
                        @if ($mapping->isMapped())
                            <code>{{ $mapping->permission->name }}</code>
                        @else
                            <x-ui.badge variant="danger" dot>belum dipetakan</x-ui.badge>
                        @endif
                    @endunless
                </x-ui.table.cell>

                <x-ui.table.cell align="right">
                    @if ($mapping->isMapped())
                        <x-can :resource="rk('mappings', ResourceAction::Update)">
                            <form method="POST" action="{{ route('admin.mappings.destroy', $mapping) }}">
                                @csrf
                                @method('DELETE')
                                <x-ui.button type="submit" variant="secondary" size="xs" title="Lepas pemetaan">
                                    Lepas
                                </x-ui.button>
                            </form>
                        </x-can>
                    @endif
                </x-ui.table.cell>
            </x-ui.table.row>
        @empty
            <tr>
                <td colspan="4">
                    <x-ui.empty-state title="Belum ada pemetaan"
                                      description="Pemetaan dibuat otomatis saat Anda membuat resource." />
                </td>
            </tr>
        @endforelse
        <x-slot:footer>{{ $mappings->links() }}</x-slot:footer>
    </x-ui.table>
</x-layouts.admin>
