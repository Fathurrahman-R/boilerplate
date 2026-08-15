@php
    $role ??= null;
    $selected = old('permissions', $role?->permissions->pluck('id')->all() ?? []);
    $selected = array_map('strval', $selected);
@endphp

<div class="space-y-6">
    <x-ui.card title="Identitas role">
        <div class="grid gap-4 sm:grid-cols-2">
            <x-ui.input name="name" label="Nama sistem" :value="$role?->name" required
                        hint="Huruf kecil tanpa spasi, mis. editor_konten. Dipakai di kode." />

            <x-ui.input name="label" label="Label tampilan" :value="$role?->label"
                        hint="Nama yang dilihat pengguna, mis. Editor Konten." />

            <div class="sm:col-span-2">
                <x-ui.textarea name="description" label="Deskripsi" :value="$role?->description" rows="2" />
            </div>
        </div>
    </x-ui.card>

    <x-ui.card title="Permission"
               subtitle="Baris adalah resource, kolom adalah aksi. Centang berarti role ini boleh melakukannya.">
        <div class="space-y-6">
            @forelse ($resources as $resource)
                <div>
                    <div class="mb-2 flex flex-wrap items-center gap-2">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $resource->label }}</h3>
                        <code class="rounded bg-gray-100 px-1.5 py-0.5 text-xs text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ $resource->key }}</code>

                        @if ($resource->group)
                            <x-ui.badge>{{ $resource->group }}</x-ui.badge>
                        @endif

                        <button type="button"
                                class="ms-auto text-xs font-medium text-blue-600 hover:underline dark:text-blue-500"
                                data-check-group="resource-{{ $resource->id }}">
                            Centang / lepas semua
                        </button>
                    </div>

                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($resource->mappings->sortBy(fn ($m) => $m->action->value) as $mapping)
                            @if ($mapping->isMapped())
                                <label class="flex items-start gap-2 rounded-lg border border-gray-200 p-3 text-sm dark:border-gray-700">
                                    <input type="checkbox"
                                           name="permissions[]"
                                           value="{{ $mapping->permission_id }}"
                                           data-group="resource-{{ $resource->id }}"
                                           @checked(in_array((string) $mapping->permission_id, $selected, true))
                                           class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">

                                    <span>
                                        <span class="block font-medium text-gray-900 dark:text-white">{{ $mapping->action->label() }}</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $mapping->permission->name }}</span>
                                    </span>
                                </label>
                            @else
                                <div class="flex items-start gap-2 rounded-lg border border-dashed border-red-300 p-3 text-sm dark:border-red-800">
                                    <span>
                                        <span class="block font-medium text-gray-400 line-through">{{ $mapping->action->label() }}</span>
                                        <span class="block text-xs text-red-600 dark:text-red-400">belum dipetakan ke permission</span>
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
                <div>
                    <h3 class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">
                        Permission lepas
                        <span class="font-normal text-gray-500 dark:text-gray-400">— tidak dipakai resource key mana pun</span>
                    </h3>

                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($loosePermissions as $permission)
                            <label class="flex items-start gap-2 rounded-lg border border-gray-200 p-3 text-sm dark:border-gray-700">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                       @checked(in_array((string) $permission->id, $selected, true))
                                       class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $permission->displayName() }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </x-ui.card>
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
