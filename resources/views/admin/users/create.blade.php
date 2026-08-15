<x-layouts.admin heading="Tambah pengguna"
                 :breadcrumb="['Pengguna' => route('admin.users.index'), 'Tambah' => null]">
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
        @csrf

        @include('admin.users.form', ['roles' => $roles])

        <div class="flex items-center gap-2">
            <x-ui.button type="submit">Simpan</x-ui.button>
            <x-ui.button :href="route('admin.users.index')" variant="secondary">Batal</x-ui.button>
        </div>
    </form>
</x-layouts.admin>
