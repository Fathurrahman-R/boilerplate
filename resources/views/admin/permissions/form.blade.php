@php($permission ??= null)

<x-ui.card title="Data permission">
    <div class="grid gap-4 sm:grid-cols-2">
        <x-ui.input name="name" label="Nama" :value="$permission?->name" required
                    hint="Dipakai saat pengecekan izin, mis. laporan.export atau akses-keuangan." />

        <x-ui.input name="label" label="Label tampilan" :value="$permission?->label"
                    hint="Nama yang enak dibaca di daftar permission." />

        <x-ui.input name="group" label="Grup" :value="$permission?->group"
                    hint="Untuk mengelompokkan permission di UI." />

        <div class="sm:col-span-2">
            <x-ui.textarea name="description" label="Deskripsi" :value="$permission?->description" rows="2" />
        </div>
    </div>
</x-ui.card>

@if ($permission && $permission->mappings->isNotEmpty())
    <x-ui.card title="Dipakai resource key" subtitle="Mengganti nama permission tidak memutus pemetaan ini." class="mt-6">
        <ul class="space-y-2 text-sm">
            @foreach ($permission->mappings as $mapping)
                <li class="flex items-center gap-2">
                    <x-ui.icon name="link" class="h-4 w-4 text-gray-400" />
                    <code>{{ $mapping->key() }}</code>
                </li>
            @endforeach
        </ul>
    </x-ui.card>
@endif
