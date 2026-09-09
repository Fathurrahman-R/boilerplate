@php
    $role ??= null;
    $selected = old('permissions', $role?->permissions->pluck('id')->all() ?? []);
    $selected = array_map('strval', $selected);
@endphp

<div class="flex flex-col gap-7">
    <x-ui.list title="Identitas role" class="measure"
               hint="Nama sistem ditulis huruf kecil tanpa spasi (mis. editor_konten) dan dipakai di kode; label adalah nama yang dilihat pengguna.">
        <x-ui.form-row label="Nama sistem" for="name" required>
            <x-ui.input name="name" id="name" required :value="$role?->name" aria-label="Nama sistem" />
        </x-ui.form-row>

        <x-ui.form-row label="Label tampilan" for="label">
            <x-ui.input name="label" id="label" :value="$role?->label" aria-label="Label tampilan" />
        </x-ui.form-row>

        <x-ui.form-row label="Deskripsi" for="description" stacked>
            <x-ui.textarea name="description" id="description" :value="$role?->description" rows="2"
                           aria-label="Deskripsi" />
        </x-ui.form-row>
    </x-ui.list>

    {{--
        Matriks izin tetap matriks: ini tabel keputusan, dan bentuknya memang
        benar. Yang berubah cuma wadahnya — tiap resource jadi satu grup
        daftar dengan tombol centang-semua sebagai baris terakhirnya, bukan
        tombol kecil yang menempel di judul.
    --}}
    <x-ui.list title="Permission"
               hint="Baris adalah resource, kolom adalah aksi. Centang berarti role ini boleh melakukannya.">
        <div class="flex flex-col">
            @forelse ($resources as $resource)
                <div class="relative px-4 py-3
                            not-first:before:absolute not-first:before:inset-x-4 not-first:before:top-0
                            not-first:before:h-px not-first:before:bg-line not-first:before:content-['']">
                    <div class="mb-2.5 flex flex-wrap items-center gap-2">
                        <h3 class="text-base font-semibold text-ink">{{ $resource->label }}</h3>
                        <code class="rounded-sm bg-code px-1.5 py-0.5 font-mono text-xs text-code-ink">{{ $resource->key }}</code>

                        @if ($resource->group)
                            <x-ui.badge>{{ $resource->group }}</x-ui.badge>
                        @endif

                        <button type="button"
                                class="ms-auto text-sm font-medium text-link hover:underline"
                                data-check-group="resource-{{ $resource->id }}">
                            Centang / lepas semua
                        </button>
                    </div>

                    <div class="grid gap-1.5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($resource->mappings->sortBy(fn ($m) => $m->action->value) as $mapping)
                            @if ($mapping->isMapped())
                                <label class="flex cursor-pointer items-start gap-2.5 rounded-md bg-fill-4 p-2.5 text-base transition-colors duration-[--dur-fast] hover:bg-fill-3">
                                    <input type="checkbox"
                                           name="permissions[]"
                                           value="{{ $mapping->permission_id }}"
                                           data-group="resource-{{ $resource->id }}"
                                           @checked(in_array((string) $mapping->permission_id, $selected, true))
                                           class="form-check mt-0.5">

                                    <span class="min-w-0">
                                        <span class="block font-medium text-ink">{{ $mapping->action->label() }}</span>
                                        <span class="block truncate text-xs text-ink-muted">{{ $mapping->permission->name }}</span>
                                    </span>
                                </label>
                            @else
                                <div class="flex items-start gap-2.5 rounded-md border border-dashed border-danger p-2.5 text-base">
                                    <span class="min-w-0">
                                        <span class="block font-medium text-ink-muted line-through">{{ $mapping->action->label() }}</span>
                                        <span class="block text-xs text-danger">belum dipetakan ke permission</span>
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @empty
                <x-ui.empty-state title="Belum ada resource"
                                  description="Buat resource lebih dulu supaya permission-nya bisa dibagikan ke role." />
            @endforelse

            @if ($loosePermissions->isNotEmpty())
                <div class="relative px-4 py-3
                            before:absolute before:inset-x-4 before:top-0 before:h-px before:bg-line before:content-['']">
                    <h3 class="mb-2.5 text-base font-semibold text-ink">
                        Permission lepas
                        <span class="font-normal text-ink-muted">— tidak dipakai resource key mana pun</span>
                    </h3>

                    <div class="grid gap-1.5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($loosePermissions as $permission)
                            <label class="flex cursor-pointer items-start gap-2.5 rounded-md bg-fill-4 p-2.5 text-base transition-colors duration-[--dur-fast] hover:bg-fill-3">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                       @checked(in_array((string) $permission->id, $selected, true))
                                       class="form-check mt-0.5">
                                <span class="font-medium text-ink">{{ $permission->displayName() }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </x-ui.list>
</div>

@push('scripts')
    <script>
        document.addEventListener('click', function (event) {
            var trigger = event.target.closest('[data-check-group]');

            if (! trigger) {
                return;
            }

            var boxes = document.querySelectorAll('[data-group="' + trigger.dataset.checkGroup + '"]');
            var allChecked = Array.from(boxes).every(function (box) { return box.checked; });

            boxes.forEach(function (box) { box.checked = ! allChecked; });
        });
    </script>
@endpush
