{{--
    Ringkasan error validasi untuk form auth.

    Field-nya sendiri sudah menampilkan pesan masing-masing, tapi kegagalan
    login datang tanpa terikat field tertentu, jadi tetap perlu tempat tampil.
--}}

@if ($errors->any())
    <x-ui.alert variant="danger">
        @if ($errors->count() === 1)
            {{ $errors->first() }}
        @else
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        @endif
    </x-ui.alert>
@endif
