@php($permission ??= null)

<div class="measure flex flex-col gap-7">
    <x-ui.list title="Data permission"
               hint="Nama dipakai saat pengecekan izin (mis. laporan.export); label dan grup hanya mengatur tampilannya di daftar.">
        <x-ui.form-row label="Nama" for="name" required>
            <x-ui.input name="name" id="name" required :value="$permission?->name" aria-label="Nama" />
        </x-ui.form-row>

        <x-ui.form-row label="Label tampilan" for="label">
            <x-ui.input name="label" id="label" :value="$permission?->label" aria-label="Label tampilan" />
        </x-ui.form-row>

        <x-ui.form-row label="Grup" for="group">
            <x-ui.input name="group" id="group" :value="$permission?->group" aria-label="Grup" />
        </x-ui.form-row>

        <x-ui.form-row label="Deskripsi" for="description" stacked>
            <x-ui.textarea name="description" id="description" :value="$permission?->description" rows="2"
                           aria-label="Deskripsi" />
        </x-ui.form-row>
    </x-ui.list>

    @if ($permission && $permission->mappings->isNotEmpty())
        <x-ui.list title="Dipakai resource key"
                   hint="Mengganti nama permission tidak memutus pemetaan ini.">
            @foreach ($permission->mappings as $mapping)
                <x-ui.list-row>
                    <x-slot:leading>
                        <x-ui.icon name="link" class="size-4" />
                    </x-slot:leading>

                    <code class="font-mono text-sm text-ink">{{ $mapping->key() }}</code>
                </x-ui.list-row>
            @endforeach
        </x-ui.list>
    @endif
</div>
