<x-layouts.admin heading="Tambah pengguna"
                 :breadcrumb="['Pengguna' => route('admin.users.index'), 'Tambah' => null]">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        @include('admin.users.form', ['roles' => $roles])

        <div class="mat-base mt-4 flex items-center gap-3 rounded-lg border border-line px-[26px] py-[13px]">
            <span class="flex-1 text-base2 text-ink-muted">Isi form lalu simpan untuk membuat akun.</span>
            <x-ui.button :href="route('admin.users.index')" variant="secondary" class="h-[34px]">Batal</x-ui.button>
            <x-ui.button type="submit" class="h-[34px]">Simpan</x-ui.button>
        </div>
    </form>
</x-layouts.admin>
