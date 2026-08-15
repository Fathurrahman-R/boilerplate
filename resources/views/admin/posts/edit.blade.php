<x-layouts.admin :heading="'Ubah '.$post->title"
                 :breadcrumb="['Artikel' => route('admin.posts.index'), 'Ubah' => null]">
    <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="space-y-6">
        @csrf
        @method('PUT')

        @include('admin.posts.form', ['post' => $post, 'statuses' => $statuses])

        <div class="flex items-center gap-2">
            <x-ui.button type="submit">Simpan perubahan</x-ui.button>
            <x-ui.button :href="route('admin.posts.index')" variant="secondary">Batal</x-ui.button>
        </div>
    </form>
</x-layouts.admin>
