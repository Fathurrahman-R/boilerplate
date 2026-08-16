@php use App\Enums\ResourceAction; @endphp

<x-layouts.admin :heading="$resource->label"
                 :description="$resource->description"
                 :breadcrumb="['Resource' => route('admin.resources.index'), $resource->key => null]">
    <x-slot:actions>
        <x-can :resource="rk('mappings', ResourceAction::View)">
            <x-ui.button :href="route('admin.mappings.index', ['resource' => $resource->key])" variant="secondary" size="sm">
                <x-ui.icon name="link" class="h-4 w-4" />
                Atur pemetaan
            </x-ui.button>
        </x-can>

        <x-can :resource="rk('resources', ResourceAction::Update)">
            <x-ui.button :href="route('admin.resources.edit', $resource)" size="sm">
                <x-ui.icon name="pencil" class="h-4 w-4" />
                Ubah
            </x-ui.button>
        </x-can>
    </x-slot:actions>

    <x-ui.card title="Resource key" subtitle="Salin key ini ke route, Blade, atau policy.">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs uppercase text-ink-muted">
                    <tr>
                        <th class="pb-2 pr-4">Resource key</th>
                        <th class="pb-2 pr-4">Permission</th>
                        <th class="pb-2">Dimiliki role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($resource->mappings->sortBy(fn ($m) => $m->action->value) as $mapping)
                        <tr>
                            <td class="py-3 pr-4">
                                <code class="rounded-sm bg-code px-2 py-1 font-mono text-code-ink">{{ $mapping->key() }}</code>
                                <span class="ms-2 text-xs text-ink-muted">{{ $mapping->action->label() }}</span>
                            </td>

                            <td class="py-3 pr-4">
                                @if ($mapping->isMapped())
                                    <code>{{ $mapping->permission->name }}</code>
                                @else
                                    <x-ui.badge variant="danger" dot>belum dipetakan</x-ui.badge>
                                @endif
                            </td>

                            <td class="py-3">
                                @if ($mapping->isMapped() && $mapping->permission->roles->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($mapping->permission->roles as $role)
                                            <x-ui.badge variant="primary">{{ $role->name }}</x-ui.badge>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-ink-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.card>

    <x-ui.card title="Cara memakainya" class="mt-6">
        <div class="space-y-4 text-sm">
            <div>
                <p class="mb-1 font-medium text-ink">Menjaga route</p>
<pre class="overflow-x-auto rounded-md border border-line bg-code p-4 font-mono text-xs text-code-ink"><code>Route::get('/{{ $resource->key }}', ...)
    -&gt;middleware('resource:{{ $resource->key }}.{{ ResourceAction::View->value }}');</code></pre>
            </div>

            <div>
                <p class="mb-1 font-medium text-ink">Menyembunyikan tombol</p>
<pre class="overflow-x-auto rounded-md border border-line bg-code p-4 font-mono text-xs text-code-ink"><code>&lt;x-can resource="{{ $resource->key }}.{{ ResourceAction::Create->value }}"&gt;
    &lt;x-ui.button&gt;Tambah&lt;/x-ui.button&gt;
&lt;/x-can&gt;</code></pre>
            </div>

            <div>
                <p class="mb-1 font-medium text-ink">Di kode PHP</p>
<pre class="overflow-x-auto rounded-md border border-line bg-code p-4 font-mono text-xs text-code-ink"><code>rk('{{ $resource->key }}', ResourceAction::Update);</code></pre>
            </div>
        </div>
    </x-ui.card>
</x-layouts.admin>
