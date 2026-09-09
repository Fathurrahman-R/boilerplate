@php
    $resource ??= null;

    $checked = old('actions', $resource
        ? $resource->mappings->map(fn ($m) => $m->action->value)->all()
        : array_map(fn ($a) => $a->value, array_filter($actions, fn ($a) => $a->isDefault())));

    $checked = array_map('strval', $checked);
@endphp

<div class="measure flex flex-col gap-7" x-data="{ key: @js(old('key', $resource?->key ?? '')) }">
    <x-ui.list title="Identitas resource"
               hint="Nama resource ditulis huruf kecil tanpa spasi, mis. laporan_bulanan. Aksinya tidak perlu diketik — pilih di grup di bawah.">
        <x-ui.form-row label="Nama resource" for="key" required>
            <x-ui.input name="key" id="key" required :value="$resource?->key" x-model="key" aria-label="Nama resource" />
        </x-ui.form-row>

        <x-ui.form-row label="Label tampilan" for="label" required>
            <x-ui.input name="label" id="label" required :value="$resource?->label" aria-label="Label tampilan" />
        </x-ui.form-row>

        <x-ui.form-row label="Grup" for="group">
            <x-ui.input name="group" id="group" :value="$resource?->group" aria-label="Grup" />
        </x-ui.form-row>

        <x-ui.form-row label="Deskripsi" for="description" stacked>
            <x-ui.textarea name="description" id="description" :value="$resource?->description" rows="2"
                           aria-label="Deskripsi" />
        </x-ui.form-row>
    </x-ui.list>

    {{--
        Pratinjau berdiri tepat di bawah field yang membentuknya dan di atas
        daftar aksi yang mengisinya: akibat sebuah pilihan ditaruh sedekat
        mungkin dengan tempat pilihan itu dibuat.
    --}}
    <x-ui.list title="Pratinjau resource key"
               hint="Inilah string yang nanti dipakai di route, Blade, dan menu.">
        <x-ui.list-row>
            <div class="flex flex-wrap gap-2" id="key-preview">
                <template x-for="action in $store.selectedActions.list" :key="action">
                    <code class="rounded-sm bg-code px-2 py-1 font-mono text-sm text-code-ink"
                          x-text="(key || 'resource') + '.' + action"></code>
                </template>
            </div>

            <p class="text-sm text-ink-muted" x-show="$store.selectedActions.list.length === 0">
                Belum ada aksi yang dipilih.
            </p>
        </x-ui.list-row>
    </x-ui.list>

    <x-ui.list title="Aksi"
               hint="Setiap aksi yang dicentang otomatis dibuatkan permission dan langsung dipetakan.">
        @foreach ($actions as $action)
            <x-ui.form-row>
                <label class="flex w-full cursor-pointer items-center gap-3">
                    <span class="min-w-0 flex-1">
                        <span class="flex flex-wrap items-center gap-2 text-base font-medium text-ink">
                            {{ $action->label() }}
                            <code class="font-mono text-xs font-normal text-ink-muted">{{ $action->value }}</code>
                            @if ($action->isDestructive())
                                <x-ui.badge variant="danger" pill>berisiko</x-ui.badge>
                            @endif
                        </span>
                        <span class="mt-0.5 block text-sm text-ink-muted">{{ $action->description() }}</span>
                    </span>

                    <input type="checkbox" name="actions[]" value="{{ $action->value }}"
                           @checked(in_array($action->value, $checked, true))
                           x-on:change="$store.selectedActions.toggle('{{ $action->value }}', $event.target.checked)"
                           x-init="$store.selectedActions.toggle('{{ $action->value }}', $el.checked)"
                           class="form-check">
                </label>
            </x-ui.form-row>
        @endforeach
    </x-ui.list>

    @error('actions')
        <p class="px-4 text-sm text-danger">{{ $message }}</p>
    @enderror
</div>

@push('scripts')
    <script>
        // Daftar aksi terpilih dipakai bersama oleh kolom centang dan pratinjau
        // key, jadi disimpan di store Alpine, bukan di salah satu komponen.
        document.addEventListener('alpine:init', function () {
            Alpine.store('selectedActions', {
                list: [],
                toggle: function (action, checked) {
                    var index = this.list.indexOf(action);

                    if (checked && index === -1) {
                        this.list.push(action);
                    }

                    if (! checked && index !== -1) {
                        this.list.splice(index, 1);
                    }
                },
            });
        });
    </script>
@endpush
