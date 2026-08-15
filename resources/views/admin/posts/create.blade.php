<x-layouts.admin heading="Tulis artikel"
                 :breadcrumb="['Artikel' => route('admin.posts.index'), 'Tulis' => null]">
    <form method="POST" action="{{ route('admin.posts.store') }}" class="space-y-6">
        @csrf

        @include('admin.posts.form', ['statuses' => $statuses])

        <div class="flex items-center gap-2">
            <x-ui.button type="submit">Simpan</x-ui.button>
            <x-ui.button :href="route('admin.posts.index')" variant="secondary">Batal</x-ui.button>
        </div>
    </form>
</x-layouts.admin>
