<x-layouts.admin :heading="'Ubah '.$user->name"
                 :breadcrumb="['Pengguna' => route('admin.users.index'), 'Ubah' => null]">
    <form method="POST" action="{{ route('admin.users.update', $user) }}"
          x-data="{ dirty: false }" x-on:input="dirty = true" x-on:change="dirty = true">
        @csrf
        @method('PUT')

        @include('admin.users.form', ['user' => $user, 'roles' => $roles])

        <div class="mat-base mt-4 flex items-center gap-3 rounded-lg border border-line px-[26px] py-[13px]">
            <span class="flex-1 text-base2 text-ink-muted"
                  x-text="dirty ? 'Ada perubahan belum disimpan' : 'Semua perubahan tersimpan'"></span>
            <x-ui.button :href="route('admin.users.index')" variant="secondary" class="h-[34px]">Batal</x-ui.button>
            <x-ui.button type="submit" class="h-[34px]">Simpan perubahan</x-ui.button>
        </div>
    </form>
</x-layouts.admin>
