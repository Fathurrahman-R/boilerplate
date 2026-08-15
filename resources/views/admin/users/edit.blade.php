<x-layouts.admin :heading="'Ubah '.$user->name"
                 :breadcrumb="['Pengguna' => route('admin.users.index'), 'Ubah' => null]">
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
        @csrf
        @method('PUT')

        @include('admin.users.form', ['user' => $user, 'roles' => $roles])

        <div class="flex items-center gap-2">
            <x-ui.button type="submit">Simpan perubahan</x-ui.button>
            <x-ui.button :href="route('admin.users.index')" variant="secondary">Batal</x-ui.button>
        </div>
    </form>
</x-layouts.admin>
